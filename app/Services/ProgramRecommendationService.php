<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\User;
use App\Notifications\ProgramSpotlightNotification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Finds programs that are a great fit for a parent's children
 * and fires non-intrusive spotlight notifications.
 */
class ProgramRecommendationService
{
    /**
     * Return an array of spotlight recommendations for the given parent.
     * Each entry: ['program' => Program, 'child' => User, 'reason' => string]
     * Limited to 1 random pick so the UI stays non-intrusive.
     */
    public function getSpotlightForParent(User $parent): ?array
    {
        $children = $parent->children;

        if ($children->isEmpty()) {
            return null;
        }

        $candidates = collect();

        foreach ($children as $child) {
            $age = $child->age;
            if ($age === null) continue;

            // Programs that match this child's age and they aren't already enrolled in
            $matches = Program::where('status', 'active')
                ->where(function ($q) use ($age) {
                    $q->where(function ($sq) use ($age) {
                        // Scheduled / Journey / Offline: range-based
                        $sq->whereIn('type', ['scheduled', 'journey', 'offline'])
                            ->where(function ($rq) use ($age) {
                                $rq->where(function ($minQ) use ($age) {
                                    $minQ->whereNull('cohort_age_min')
                                        ->orWhere('cohort_age_min', '<=', $age);
                                })->where(function ($maxQ) use ($age) {
                                    $maxQ->whereNull('cohort_age_max')
                                        ->orWhere('cohort_age_max', '>=', $age);
                                });
                            });
                    })->orWhere(function ($sq) use ($age) {
                        // Rolling: exact age target
                        $sq->where('type', 'rolling')
                            ->where(function ($rq) use ($age) {
                                $rq->whereNull('age_target')
                                    ->orWhere('age_target', $age);
                            });
                    });
                })
                ->whereDoesntHave('enrollments', fn($eq) => $eq->where('user_id', $child->id))
                ->get();

            foreach ($matches as $program) {
                $reason = $this->buildReason($program, $child);
                $candidates->push([
                    'program' => $program,
                    'child'   => $child,
                    'reason'  => $reason,
                    'score'   => $this->scoreMatch($program, $child),
                ]);
            }
        }

        if ($candidates->isEmpty()) {
            return null;
        }

        // Pick the highest-scored candidate (deterministic but feels personalised)
        $best = $candidates->sortByDesc('score')->first();

        return $best;
    }

    /**
     * Send a database (+ push payload) notification to the parent
     * only if they haven't been sent one for this program in the last 7 days.
     */
    public function maybeSendSpotlightNotification(User $parent): void
    {
        $spotlight = $this->getSpotlightForParent($parent);
        if (!$spotlight) return;

        /** @var Program $program */
        $program = $spotlight['program'];
        $child   = $spotlight['child'];
        $reason  = $spotlight['reason'];

        // Throttle: skip if already notified about this program recently
        $alreadySent = $parent->notifications()
            ->where('type', ProgramSpotlightNotification::class)
            ->where('created_at', '>=', now()->subDays(7))
            ->whereJsonContains('data->program_id', $program->id)
            ->exists();

        if ($alreadySent) return;

        $parent->notify(new ProgramSpotlightNotification($program, $child, $reason));
    }

    // ─── Private helpers ────────────────────────────────────────────────────

    private function buildReason(Program $program, User $child): string
    {
        $age = $child->age;

        if ($program->is_free) {
            return "Free access — great for {$child->first_name} at age {$age}!";
        }

        if ($program->type === 'rolling') {
            return "Designed specifically for age {$age} — a perfect match for {$child->first_name}.";
        }

        if ($program->start_date && Carbon::parse($program->start_date)->isFuture()) {
            $starts = Carbon::parse($program->start_date)->diffForHumans();
            return "Starting {$starts} — enrol {$child->first_name} before it fills up!";
        }

        if ($program->type === 'journey') {
            return "{$child->first_name} can start at their own pace — no rush!";
        }

        return "A great fit for {$child->first_name}'s learning journey.";
    }

    private function scoreMatch(Program $program, User $child): int
    {
        $score = 0;

        // Age-exact rolling match is highest signal
        if ($program->type === 'rolling' && $program->age_target == $child->age) {
            $score += 50;
        }

        // Free programs are more likely to convert
        if ($program->is_free) $score += 20;

        // Featured programs are promoted
        if ($program->is_featured) $score += 15;

        // Upcoming programmes feel urgent
        if ($program->start_date && Carbon::parse($program->start_date)->isFuture()
            && Carbon::parse($program->start_date)->diffInDays(now()) <= 30) {
            $score += 25;
        }

        return $score;
    }
}
