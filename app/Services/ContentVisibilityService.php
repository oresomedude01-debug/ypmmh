<?php

namespace App\Services;

use App\Models\User;
use App\Models\Program;
use App\Models\ProgramContent;
use Carbon\Carbon;

class ContentVisibilityService
{
    /**
     * Get all visible content for a child.
     */
    public function getVisibleContentForChild(User $child)
    {
        $visibleContent = collect();

        $programs = $child->programs()->where('status', 'active')->get();

        foreach ($programs as $program) {
            if ($program->type === 'rolling') {
                $visibleContent = $visibleContent->merge(
                    $this->getRollingContent($child, $program)
                );
            }

            if ($program->type === 'scheduled') {
                $visibleContent = $visibleContent->merge(
                    $this->getScheduledContent($program)
                );
            }
        }

        return $visibleContent->sortBy('visible_at')->values();
    }

    /**
     * Get visible content for a rolling (age-progressive) program.
     */
    protected function getRollingContent(User $child, Program $program)
    {
        if (!$child->date_of_birth) {
            return collect();
        }

        $now = Carbon::now();
        $dob = Carbon::parse($child->date_of_birth);

        $contents = ProgramContent::where('program_id', $program->id)
            ->whereNotNull('target_age')
            ->whereNotNull('week_number')
            ->whereNotNull('day_number')
            ->whereNotNull('time_of_day')
            ->where('is_active', true)
            ->get();

        return $contents->filter(function ($content) use ($dob, $now) {
            $visibleAt = $this->calculateRollingVisibleAt(
                $dob,
                $content->target_age,
                $content->week_number,
                $content->day_number,
                $content->time_of_day
            );

            return $visibleAt && $visibleAt->lessThanOrEqualTo($now);
        })->map(function ($content) use ($dob) {
            $content->visible_at = $this->calculateRollingVisibleAt(
                $dob,
                $content->target_age,
                $content->week_number,
                $content->day_number,
                $content->time_of_day
            );

            return $content;
        });
    }

    /**
     * Get visible content for a scheduled (cohort) program.
     */
    protected function getScheduledContent(Program $program)
    {
        $now = Carbon::now();

        $contents = ProgramContent::where('program_id', $program->id)
            ->whereNotNull('publish_at')
            ->where('is_active', true)
            ->where('publish_at', '<=', $now)
            ->get();

        return $contents->map(function ($content) {
            $content->visible_at = $content->publish_at;
            return $content;
        });
    }

    /**
     * Calculate when rolling content becomes visible for a child.
     */
    protected function calculateRollingVisibleAt(
        Carbon $dob,
        int $targetAge,
        int $weekNumber,
        int $dayNumber,
        string $timeOfDay
    ) {
        if ($targetAge < 0 || $weekNumber < 1 || $dayNumber < 0) {
            return null;
        }

        // Birthday when child turns target age
        $ageBirthday = $dob->copy()->addYears($targetAge);

        // Add week/day offsets
        $visibleDate = $ageBirthday
            ->copy()
            ->addWeeks($weekNumber - 1)
            ->addDays($dayNumber);

        // Combine with time
        [$hour, $minute, $second] = array_pad(
            explode(':', $timeOfDay),
            3,
            0
        );

        return $visibleDate->setTime($hour, $minute, $second);
    }
}
