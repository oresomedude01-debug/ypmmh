<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MentorDashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();

        // Stats for the mentor
        // 1. Total programmes assigned to this mentor
        $assignedPrograms = Program::where('mentor_id', $mentor->id)->get();
        $totalPrograms = $assignedPrograms->count();

        // 2. Total active students under this mentor's programmes
        $studentIds = \App\Models\Enrollment::whereIn('program_id', $assignedPrograms->pluck('id'))
            ->pluck('user_id')
            ->unique();
        $totalStudents = $studentIds->count();

        // 3. New enrollments this month
        $newEnrollments = \App\Models\Enrollment::whereIn('program_id', $assignedPrograms->pluck('id'))
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $stats = [
            'totalStudents' => $totalStudents,
            'totalPrograms' => $totalPrograms,
            'newEnrollments' => $newEnrollments,
            'totalSessions' => 0, // Placeholder
        ];

        // Fetch unread notifications for the mentor
        $notifications = $mentor->unreadNotifications->take(5);

        // Fetch upcoming events
        $upcomingEvents = Event::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        // Enrollment distribution across assigned programs
        $programStats = [];
        foreach ($assignedPrograms as $program) {
            $programStats[] = [
                'name' => $program->name,
                'count' => $program->enrollments()->count()
            ];
        }

        // Recent students in mentor's assigned programs
        $recentStudents = User::whereIn('id', $studentIds)
            ->with([
                'enrollments' => function ($query) use ($assignedPrograms) {
                    $query->whereIn('program_id', $assignedPrograms->pluck('id'));
                },
                'enrollments.program'
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('mentor.dashboard', [
            'stats' => $stats,
            'programStats' => $programStats,
            'recentStudents' => $recentStudents,
            'notifications' => $notifications,
            'upcomingEvents' => $upcomingEvents,
            'assignedPrograms' => $assignedPrograms,
        ]);
    }

    public function showChild(User $child)
    {
        $mentor = Auth::user();

        // Check if mentor has access to this child
        $hasAccess = $child->programs()->where('mentor_id', $mentor->id)->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this student profile.');
        }

        $child->load([
            'programs' => function ($query) use ($mentor) {
                $query->where('mentor_id', $mentor->id);
            },
            'programs.enrollments',
            'parent',
            'observations' => function ($query) use ($mentor) {
                $query->where('mentor_id', $mentor->id)->latest();
            }
        ]);

        return view('mentor.children.show', [
            'child' => $child,
        ]);
    }

    public function storeObservation(Request $request, User $child)
    {
        $mentor = Auth::user();

        // Security check
        $hasAccess = $child->programs()->where('mentor_id', $mentor->id)->exists();
        if (!$hasAccess) {
            abort(403);
        }

        $validated = $request->validate([
            'observation' => 'required|string|min:10',
        ]);

        \App\Models\Observation::create([
            'mentor_id' => $mentor->id,
            'child_id' => $child->id,
            'content' => $validated['observation'],
        ]);

        return back()->with('success', 'Educational observation recorded successfully.');
    }
}
