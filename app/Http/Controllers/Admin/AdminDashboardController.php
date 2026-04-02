<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Program;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $stats = [
            'totalChildren' => User::role('Child')->count(),
            'totalMentors' => User::role('Mentor')->count(),
            'totalPrograms' => Program::count(),
            'totalEnrollments' => \App\Models\Enrollment::count(),
        ];

        // Fetch unread notifications for the admin
        $notifications = $admin ? $admin->unreadNotifications->take(5) : collect();

        // Fetch upcoming events
        $upcomingEvents = Event::where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        // Enrollment trends for the last 6 months
        $enrollmentTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $enrollmentTrends[] = [
                'month' => $month->format('M'),
                'count' => \App\Models\Enrollment::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count()
            ];
        }

        // Program distribution
        $programDistribution = [
            'rolling' => Program::where('type', 'rolling')->count(),
            'scheduled' => Program::where('type', 'scheduled')->count(),
        ];

        // Recent students
        $recentStudents = User::role('Child')
            ->with('enrollments.program')
            ->latest()
            ->take(5)
            ->get();

        // Recent activities (simplified for demo - using recent enrollments)
        $recentActivities = \App\Models\Enrollment::with(['user', 'program'])
            ->latest()
            ->take(6)
            ->get();

        return view('Admin.dashboard', [
            'stats' => $stats,
            'enrollmentTrends' => $enrollmentTrends,
            'programDistribution' => $programDistribution,
            'recentStudents' => $recentStudents,
            'recentActivities' => $recentActivities,
            'notifications' => $notifications,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function reports()
    {
        $reports = \App\Models\Report::with('reporter')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => \App\Models\Report::count(),
            'pending' => \App\Models\Report::where('status', 'pending')->count(),
            'resolved' => \App\Models\Report::where('status', 'resolved')->count(),
            'dismissed' => \App\Models\Report::where('status', 'dismissed')->count(),
        ];

        return view('Admin.Reports.index', compact('reports', 'stats'));
    }

    public function comingSoon()
    {
        return view('Admin/coming-soon');
    }
}
