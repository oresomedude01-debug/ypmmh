@extends('layouts.dashboard')

@section('title', 'Mentor Dashboard')

@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .analytics-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
        }

        .analytics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px var(--shadow-color);
            border-color: var(--primary-500);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Themed utility overrides for dark mode premium feel */
        [data-theme="dark"] .stat-icon-bg {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Mentor Portal
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Welcome back, {{ auth()->user()->first_name }}.
                    Here is what's happening in your programmes.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-secondary glass">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Schedule Session</span>
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Your Students -->
            <div class="analytics-card rounded-2xl p-4 sm:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span
                            class="px-2 py-0.5 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                    </div>
                    <p class="text-[8px] sm:text-sm font-bold uppercase tracking-wider mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">Students</p>
                    <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">
                        {{ number_format($stats['totalStudents']) }}</h3>
                </div>
            </div>

            <!-- Assigned Programs -->
            <div class="analytics-card rounded-2xl p-4 sm:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl stat-icon-bg bg-purple-50 text-purple-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                    <p class="text-[8px] sm:text-sm font-bold uppercase tracking-wider mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
                        Programmes</p>
                    <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">
                        {{ number_format($stats['totalPrograms']) }}</h3>
                </div>
            </div>

            <!-- New Enrollments -->
            <div class="analytics-card rounded-2xl p-4 sm:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl stat-icon-bg bg-amber-50 text-amber-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <p class="text-[8px] sm:text-sm font-bold uppercase tracking-wider mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">New
                        this Month</p>
                    <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">
                        {{ number_format($stats['newEnrollments']) }}</h3>
                </div>
            </div>

            <!-- Completed Sessions (Placeholder) -->
            <div class="analytics-card rounded-2xl p-4 sm:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl stat-icon-bg bg-emerald-50 text-emerald-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <p class="text-[8px] sm:text-sm font-bold uppercase tracking-wider mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
                        Sessions Held</p>
                    <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">
                        {{ number_format($stats['totalSessions']) }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Programme Distribution Chart -->
            <div class="glass rounded-3xl p-4 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--text-primary);">Student Distribution</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Students across your assigned programmes
                        </p>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="programStatsChart"></canvas>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="glass rounded-3xl p-4 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--text-primary);">Upcoming Events</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Sessions and workshops</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($upcomingEvents as $event)
                        <div class="p-4 rounded-2xl border transition-all flex items-center gap-4"
                            style="background-color: var(--bg-primary); border-color: var(--border-color);">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex flex-col items-center justify-center flex-shrink-0">
                                <span
                                    class="text-[10px] font-black uppercase leading-none">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                                <span
                                    class="text-lg font-black leading-none mt-1">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold truncate" style="color: var(--text-primary);">{{ $event->title }}
                                </h4>
                                <p class="text-xs font-medium" style="color: var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} •
                                    {{ $event->location ?? 'Main Hall' }}</p>
                            </div>
                            <span
                                class="text-[10px] font-black uppercase px-2 py-1 rounded-lg bg-[#0B4D73]/5 text-[#0B4D73] border border-[#0B4D73]/10">{{ $event->type }}</span>
                        </div>
                    @empty
                        <div class="py-12 text-center opacity-20">
                            <i class="fas fa-calendar-alt text-4xl mb-3" style="color: var(--text-primary);"></i>
                            <p class="text-xs font-black uppercase tracking-widest" style="color: var(--text-primary);">No
                                upcoming events</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="glass rounded-3xl overflow-hidden shadow-sm flex flex-col">
            <div class="p-6 border-b bg-opacity-5 flex items-center justify-between font-bold px-8"
                style="border-color: var(--border-color); background-color: var(--text-primary);">
                <h3 style="color: var(--text-primary);">My Recent Students</h3>
                <a href="{{ route('admin.children.index') }}"
                    class="text-xs font-black uppercase tracking-widest hover:underline"
                    style="color: var(--primary-500);">View All</a>
            </div>
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest"
                            style="background-color: var(--bg-secondary); color: var(--text-secondary);">
                            <th class="px-8 py-4" style="background: transparent;">Student</th>
                            <th class="px-8 py-4" style="background: transparent;">Assigned Programme</th>
                            <th class="px-8 py-4" style="background: transparent;">ID Number</th>
                            <th class="px-8 py-4" style="background: transparent;">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border-color);">
                        @forelse($recentStudents as $student)
                            <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black text-xs">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold" style="color: var(--text-primary);">
                                                {{ $student->first_name }} {{ $student->last_name }}</p>
                                            <p class="text-[10px]" style="color: var(--text-secondary);">{{ $student->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-xs font-bold"
                                        style="color: var(--text-primary);">{{ $student->enrollments->first()->program->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-[10px] font-mono font-black px-2 py-1 rounded-lg"
                                        style="background-color: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-color);">{{ $student->unique_number ?? 'NO-ID' }}</span>
                                </td>
                                <td class="px-8 py-4 text-xs font-bold" style="color: var(--text-secondary);">
                                    {{ $student->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center opacity-20">
                                    <p class="text-sm font-black uppercase tracking-widest" style="color: var(--text-primary);">
                                        No students assigned to your programmes yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile List -->
            <div class="md:hidden p-4 space-y-3">
                @forelse($recentStudents as $student)
                    <div class="flex items-center gap-3 pb-3 border-b last:border-0" style="border-color: var(--border-color);">
                        <div class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black text-xs shrink-0">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-black truncate" style="color: var(--text-primary);">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h4>
                                <span class="text-[9px] font-bold text-emerald-500 uppercase">Active</span>
                            </div>
                            <p class="text-[10px] truncate opacity-70 mb-1" style="color: var(--text-secondary);">
                                {{ $student->enrollments->count() > 0 ? $student->enrollments->first()->program->name : 'N/A' }}
                            </p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[9px] font-mono opacity-50">{{ $student->unique_number ?? 'NO-ID' }}</span>
                                <span class="text-[9px] font-bold" style="color: var(--text-secondary);">{{ $student->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 opacity-40">
                        <i class="fas fa-users-slash text-2xl mb-2"></i>
                        <p class="text-xs font-bold">No students found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rootStyle = getComputedStyle(document.documentElement);
            const gridColor = rootStyle.getPropertyValue('--border-color').trim() || 'rgba(0,0,0,0.1)';
            const textColor = rootStyle.getPropertyValue('--text-secondary').trim() || '#64748b';
            const primaryColor = rootStyle.getPropertyValue('--primary-500').trim() || '#0B4D73';

            const ctx = document.getElementById('programStatsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_column($programStats, 'name')) !!},
                    datasets: [{
                        label: 'Students',
                        data: {!! json_encode(array_column($programStats, 'count')) !!},
                        backgroundColor: primaryColor,
                        borderRadius: 8,
                        barThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: gridColor },
                            ticks: { font: { size: 10, weight: 600 }, color: textColor }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 600 }, color: textColor }
                        }
                    }
                }
            });
        });
    </script>
@endsection