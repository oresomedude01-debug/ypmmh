<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;

use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChildDashboardController extends Controller
{
    public function profile()
    {
        $child = Auth::user();
        $child->load('parent');

        $programs = $child->programs()->with([
            'contents' => function ($q) {
                $q->where('is_active', true);
            }
        ])->get();

        $ongoingPrograms = collect();
        $completedPrograms = collect();
        $completedProgramsCount = 0;

        foreach ($programs as $program) {
            $visibleContents = $this->getVisibleContents($program, $child);
            $visibleIds = $visibleContents->pluck('id');
            
            $totalVisible = $visibleIds->count();
            $completedCount = 0;

            if ($totalVisible > 0) {
                $completedCount = $child->lessonProgress()
                    ->whereIn('program_content_id', $visibleIds)
                    ->whereNotNull('completed_at')
                    ->count();
            }

            $program->progress_percentage = $totalVisible > 0 ? round(($completedCount / $totalVisible) * 100) : 0;

            if ($totalVisible > 0 && $completedCount >= $totalVisible) {
                $completedProgramsCount++;
                $completedPrograms->push($program);
            } else {
                $ongoingPrograms->push($program);
            }
        }

        $stats = [
            'total_lessons' => $child->lessonProgress()->whereNotNull('completed_at')->count(),
            'total_messages' => $child->programMessages()->count(),
            'completed_programs' => $completedProgramsCount,
            'xp' => $child->xp_points,
            'streak' => $child->streak,
            'level' => floor($child->xp_points / 100) + 1,
            'rank' => $child->rank,
        ];

        return view('child.profile', compact('child', 'ongoingPrograms', 'completedPrograms', 'stats'));
    }

    public function achievements()
    {
        $child = Auth::user();

        $programs = $child->programs()->with([
            'contents' => function ($q) {
                $q->where('is_active', true);
            }
        ])->get();
        $completedProgramsCount = 0;

        foreach ($programs as $program) {
            $visibleContents = $this->getVisibleContents($program, $child);
            $visibleIds = $visibleContents->pluck('id');
            if ($visibleIds->isEmpty())
                continue;

            $completedCount = $child->lessonProgress()
                ->whereIn('program_content_id', $visibleIds)
                ->whereNotNull('completed_at')
                ->count();

            if ($completedCount >= $visibleContents->count() && $visibleContents->count() > 0) {
                $completedProgramsCount++;
            }
        }

        $stats = [
            'total_lessons' => $child->lessonProgress()->whereNotNull('completed_at')->count(),
            'total_messages' => $child->programMessages()->count(),
            'total_loves_received' => $child->reactionsReceived()->where('type', 'love')->count(),
            'total_likes_received' => $child->reactionsReceived()->where('type', 'like')->count(),
            'total_given' => $child->reactionsGiven()->count(),
            'completed_programs' => $completedProgramsCount,
            'xp' => $child->xp_points,
            'streak' => $child->streak,
            'level' => floor($child->xp_points / 100) + 1,
            'rank' => $child->rank,
        ];

        // Define some badges/medals logic (Total 18)
        $medals = [
            [
                'id' => 'first_step',
                'name' => 'First Step',
                'description' => 'Completed 3 lessons to start your path!',
                'icon' => 'fa-shoe-prints',
                'color' => 'bg-emerald-500',
                'unlocked' => $stats['total_lessons'] >= 3
            ],
            [
                'id' => 'chatterbox',
                'name' => 'Circle Speaker',
                'description' => 'Contributed 50 messages to The Halaqah.',
                'icon' => 'fa-comment-dots',
                'color' => 'bg-cyan-500',
                'unlocked' => $stats['total_messages'] >= 50
            ],
            [
                'id' => 'heart_of_gold',
                'name' => 'Heart of Gold',
                'description' => 'Received 50 "Love" reactions from peers.',
                'icon' => 'fa-heart',
                'color' => 'bg-rose-500',
                'unlocked' => $stats['total_loves_received'] >= 50
            ],
            [
                'id' => 'knowledge_seeker',
                'name' => 'Knowledge Seeker',
                'description' => 'Completed 20 lessons.',
                'icon' => 'fa-book-open',
                'color' => 'bg-blue-500',
                'unlocked' => $stats['total_lessons'] >= 20
            ],
            [
                'id' => 'on_fire',
                'name' => 'Consistent Explorer',
                'description' => 'Maintained a 7-day streak!',
                'icon' => 'fa-fire',
                'color' => 'bg-orange-500',
                'unlocked' => $stats['streak'] >= 7
            ],
            [
                'id' => 'social_hero',
                'name' => 'Social Hero',
                'description' => 'Gave 100 reactions to support others!',
                'icon' => 'fa-hands-helping',
                'color' => 'bg-teal-500',
                'unlocked' => $stats['total_given'] >= 100
            ],
            [
                'id' => 'program_graduate',
                'name' => 'Program Graduate',
                'description' => 'Successfully finished your first entire program!',
                'icon' => 'fa-graduation-cap',
                'color' => 'bg-indigo-600',
                'unlocked' => $stats['completed_programs'] >= 1
            ],
            [
                'id' => 'trailblazer',
                'name' => 'Trailblazer',
                'description' => 'Completed 50 lessons on your journey.',
                'icon' => 'fa-mountain',
                'color' => 'bg-indigo-500',
                'unlocked' => $stats['total_lessons'] >= 50
            ],
            [
                'id' => 'the_popular',
                'name' => 'The Popular',
                'description' => 'Received 200 "Like" reactions!',
                'icon' => 'fa-thumbs-up',
                'color' => 'bg-blue-600',
                'unlocked' => $stats['total_likes_received'] >= 200
            ],
            [
                'id' => 'committed',
                'name' => 'The Dedicated',
                'description' => 'Maintained a 21-day activity streak!',
                'icon' => 'fa-calendar-check',
                'color' => 'bg-pink-500',
                'unlocked' => $stats['streak'] >= 21
            ],
            [
                'id' => 'voice_of_wisdom',
                'name' => 'Halaqah Legend',
                'description' => 'Contributed 200 messages in your community.',
                'icon' => 'fa-crown',
                'color' => 'bg-amber-600',
                'unlocked' => $stats['total_messages'] >= 200
            ],
            [
                'id' => 'master_of_one',
                'name' => 'Master of One',
                'description' => 'Completely finished 3 different programs!',
                'icon' => 'fa-award',
                'color' => 'bg-rose-600',
                'unlocked' => $stats['completed_programs'] >= 3
            ],
            [
                'id' => 'rising_star',
                'name' => 'Rising Star',
                'description' => 'Reached Level 10.',
                'icon' => 'fa-star',
                'color' => 'bg-yellow-500',
                'unlocked' => $stats['level'] >= 10
            ],
            [
                'id' => 'consistent_scholar',
                'name' => 'Consistent Scholar',
                'description' => 'Maintained a 50-day streak!',
                'icon' => 'fa-bolt',
                'color' => 'bg-yellow-600',
                'unlocked' => $stats['streak'] >= 50
            ],
            [
                'id' => 'circle_pillar',
                'name' => 'Circle Pillar',
                'description' => 'Sent 500 messages in The Halaqah.',
                'icon' => 'fa-hamsa',
                'color' => 'bg-purple-600',
                'unlocked' => $stats['total_messages'] >= 500
            ],
            [
                'id' => 'journey_veteran',
                'name' => 'Journey Veteran',
                'description' => 'Finished 5 entire programs!',
                'icon' => 'fa-scroll',
                'color' => 'bg-slate-700',
                'unlocked' => $stats['completed_programs'] >= 5
            ],
            [
                'id' => 'high_achiever',
                'name' => 'High Achiever',
                'description' => 'Completed 100 lessons!',
                'icon' => 'fa-medal',
                'color' => 'bg-emerald-700',
                'unlocked' => $stats['total_lessons'] >= 100
            ],
            [
                'id' => 'elite_explorer',
                'name' => 'Guardian of Knowledge',
                'description' => 'Reached high-tier Level 50!',
                'icon' => 'fa-gem',
                'color' => 'bg-blue-800',
                'unlocked' => $stats['level'] >= 50
            ],
        ];

        return view('child.achievements', compact('child', 'stats', 'medals'));
    }

    public function index()
    {
        $child = Auth::user();

        // Update streak on login/access
        $child->updateStreak();

        $programs = $child->programs()
            ->wherePivot('status', 'active')
            ->with([
                'mentor',
                'contents' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->get();

        // Track overall progress for each program based on VISIBLE contents
        $programs->each(function ($program) use ($child) {
            $visibleContents = $this->getVisibleContents($program, $child);
            $visibleIds = $visibleContents->pluck('id');

            $completedCount = $child->lessonProgress()
                ->whereIn('program_content_id', $visibleIds)
                ->whereNotNull('completed_at')
                ->count();

            $totalVisible = $visibleContents->count();

            $program->progress_percentage = $totalVisible > 0
                ? round(($completedCount / $totalVisible) * 100)
                : 0;
            $program->completed_lessons_count = $completedCount;
            $program->visible_contents_count = $totalVisible;

            $program->contents_count = $totalVisible;
        });

        $leaderboard = \App\Models\User::role('Child')
            ->orderByDesc('xp_points')
            ->take(5)
            ->get(['id', 'first_name', 'last_name', 'xp_points']);

        // Limit fully processed active programs to 4 for the dashboard
        $programs = $programs->sortByDesc(fn($p) => $p->progress_percentage)->take(4);

        return view('child.dashboard', compact('programs', 'child', 'leaderboard'));
    }

    public function programs()
    {
        $child = Auth::user();

        // Get all programs assigned to the child regardless of status (or just active/paused)
        $programs = $child->programs()
            ->with([
                'mentor',
                'contents' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->get();

        // Track overall progress for each program
        $programs->each(function ($program) use ($child) {
            $visibleContents = $this->getVisibleContents($program, $child);
            $visibleIds = $visibleContents->pluck('id');

            $completedCount = $child->lessonProgress()
                ->whereIn('program_content_id', $visibleIds)
                ->whereNotNull('completed_at')
                ->count();

            $totalVisible = $visibleContents->count();

            $program->progress_percentage = $totalVisible > 0
                ? round(($completedCount / $totalVisible) * 100)
                : 0;
            $program->completed_lessons_count = $completedCount;
            $program->visible_contents_count = $totalVisible;
            $program->contents_count = $totalVisible;
        });

        // Sort by active first, then progress
        $programs = $programs->sortByDesc(fn($p) => ($p->pivot->status === 'active' ? 1000 : 0) + $p->progress_percentage);

        return view('child.programs.index', compact('programs', 'child'));
    }

    public function communities()
    {
        $child = Auth::user();
        $programs = $child->programs()->with([
            'mentor',
            'enrollments' => function ($q) use ($child) {
                $q->where('user_id', $child->id);
            }
        ])->get();

        foreach ($programs as $program) {
            $enrollment = $program->enrollments->first();
            $lastRead = $enrollment->last_read_at ?? $enrollment->created_at;

            $program->unread_messages_count = $program->messages()
                ->where('created_at', '>', $lastRead)
                ->where('user_id', '!=', $child->id)
                ->count();
        }

        return view('child.communities.index', compact('programs'));
    }

    public function showProgram(Program $program)
    {
        $child = Auth::user();

        // Ensure child is enrolled and active
        $enrollment = $child->enrollments()->where('program_id', $program->id)->first();
        if (!$enrollment || !$enrollment->is_active) {
            abort(403, 'This program is currently paused or you are not enrolled.');
        }

        // Restrict Rolling programmes if Premium is inactive
        if ($program->type === 'rolling' && !$child->hasPremiumAccess()) {
            return redirect()->route('premium.subscribe')
                ->with('error', 'You need Premium Access to unlock this Core Path (Rolling) programme.');
        }

        $program->load(['mentor']);
        $visibleContents = $this->getVisibleContents($program, $child);

        // Set the filtered contents back to the program object for the view
        $program->setRelation('contents', $visibleContents->sortBy(function ($content) use ($program) {
            if ($program->type === 'rolling') {
                return [$content->week_number, $content->day_number, $content->time_of_day];
            } elseif ($program->type === 'journey') {
                return [$content->week_offset, $content->day_offset, $content->time_of_day];
            }
            return $content->publish_at;
        }));

        $completedLessonIds = $child->lessonProgress()
            ->whereIn('program_content_id', $program->contents->pluck('id'))
            ->whereNotNull('completed_at')
            ->pluck('program_content_id')
            ->toArray();

        return view('child.programs.show', compact('program', 'completedLessonIds'));
    }

    private function getVisibleContents($program, $child)
    {
        return $program->contents()
            ->where('is_active', true)
            ->get()
            ->filter(function ($content) use ($program, $child) {
                if ($program->type === 'rolling') {
                    $age = $program->age_target ?? 0;
                    if (!$child->date_of_birth)
                        return false;

                    $referenceDate = Carbon::parse($child->date_of_birth)->addYears($age);

                    $unlockDate = $referenceDate->copy()
                        ->addWeeks(($content->week_number ?? 1) - 1)
                        ->addDays(($content->day_number ?? 1) - 1);

                    if ($content->time_of_day) {
                        $time = explode(':', $content->time_of_day);
                        $unlockDate->setTime($time[0], $time[1] ?? 0);
                    }

                    return now()->greaterThanOrEqualTo($unlockDate);
                } elseif ($program->type === 'journey') {
                    // Check enrollment date
                    $enrollment = $child->enrollments()->where('program_id', $program->id)->first();
                    if (!$enrollment)
                        return false;

                    $referenceDate = Carbon::parse($enrollment->created_at);

                    $unlockDate = $referenceDate->copy()
                        ->addWeeks($content->week_offset ?? 0)
                        ->addDays($content->day_offset ?? 0);

                    if ($content->time_of_day) {
                        $time = explode(':', $content->time_of_day);
                        $unlockDate->setTime($time[0], $time[1] ?? 0);
                    }

                    return now()->greaterThanOrEqualTo($unlockDate);
                } else {
                    if (!$content->publish_at)
                        return true;
                    return now()->greaterThanOrEqualTo(Carbon::parse($content->publish_at));
                }
            });
    }

    public function showLesson(\App\Models\ProgramContent $lesson)
    {
        $child = Auth::user();

        // Ensure child is enrolled in the program first
        if (!$child->programs->contains($lesson->program_id)) {
            abort(403);
        }

        // Restrict Rolling programmes if Premium is inactive
        if ($lesson->program->type === 'rolling' && !$child->hasPremiumAccess()) {
            return redirect()->route('premium.subscribe')
                ->with('error', 'You need Premium Access to unlock this Core Path (Rolling) lesson.');
        }

        return view('child.lessons.show', compact('lesson'));
    }

    public function completeLesson(ProgramContent $lesson)
    {
        $child = Auth::user();

        // Ensure child is enrolled in the program first
        if (!$child->programs->contains($lesson->program_id)) {
            abort(403);
        }

        // Restrict Rolling programmes if Premium is inactive
        if ($lesson->program->type === 'rolling' && !$child->hasPremiumAccess()) {
            return redirect()->route('premium.subscribe')
                ->with('error', 'You need Premium Access to complete this Core Path (Rolling) lesson.');
        }

        $progress = $child->lessonProgress()->firstOrCreate([
            'program_content_id' => $lesson->id,
        ]);

        if (!$progress->completed_at) {
            $progress->update([
                'completed_at' => now(),
                'xp_earned' => 20, // Base XP for finishing a lesson
            ]);

            $child->addXp(20);
            $child->checkAchievements();

            // Notify Parent
            if ($child->parent) {
                $child->parent->notify(new \App\Notifications\ProgramUpdateNotification([
                    'type' => 'lesson_completion',
                    'message' => "Good news! {$child->first_name} has just completed the lesson: '{$lesson->title}'.",
                    'program_id' => $lesson->program_id,
                    'content_id' => $lesson->id,
                ]));
            }

            return back()->with('success', 'Lesson completed! +20 XP earned! 🎊');
        }

        return back();
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('child.notifications.index', compact('notifications'));
    }
}
