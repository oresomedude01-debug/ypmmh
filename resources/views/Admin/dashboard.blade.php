@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

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

        .stat-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.025em;
        }

        .activity-indicator {
            position: relative;
        }

        .activity-indicator::before {
            content: '';
            position: absolute;
            left: -21px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary-500);
            border: 2px solid var(--bg-secondary);
            z-index: 10;
        }

        .activity-line {
            position: absolute;
            left: -16px;
            top: 14px;
            bottom: -24px;
            width: 1px;
            background: var(--border-color);
        }

        /* Themed utility overrides for dark mode premium feel */
        [data-theme="dark"] .stat-icon-bg {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="dark"] .glass {
            background: var(--glass-bg);
            border-color: var(--glass-border);
        }
    </style>
@endsection

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    System Overview
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Monitoring Mentoring Hub growth and engagement.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-secondary glass">
                    <i class="fas fa-download"></i>
                    <span>Export Report</span>
                </button>
                <a href="{{ route('admin.mail.index') }}" class="btn btn-secondary glass">
                    <i class="fas fa-envelope"></i>
                    <span>Send Mail</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20">
                    <i class="fas fa-plus"></i>
                    <span>Add New User</span>
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Students -->
            <div class="admin-card p-4 sm:p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="stat-badge bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-[8px] sm:text-xs py-0.5 px-2">+8.2%</span>
                    </div>
                    <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">Students</p>
                    <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ number_format($stats['totalChildren']) }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-[#0B4D73]/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Mentors -->
            <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-purple-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="stat-badge bg-blue-500/10 text-blue-500 border border-blue-500/20 text-[8px] sm:text-xs py-0.5 px-2">Active</span>
                    </div>
                    <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">Mentors</p>
                    <h3 class="text-xl sm:text-3xl font-black text-purple-600">{{ number_format($stats['totalMentors']) }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-purple-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Programs -->
            <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-amber-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                    <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">Programmes</p>
                    <h3 class="text-xl sm:text-3xl font-black text-amber-600">{{ number_format($stats['totalPrograms']) }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-amber-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>

            <!-- Enrollments -->
            <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-emerald-500">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2 sm:mb-4">
                        <div
                            class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <span class="stat-badge bg-amber-500/10 text-amber-500 border border-amber-500/20 text-[8px] sm:text-xs py-0.5 px-2">Active</span>
                    </div>
                    <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">Enrollments</p>
                    <h3 class="text-xl sm:text-3xl font-black text-emerald-600">{{ number_format($stats['totalEnrollments']) }}</h3>
                </div>
                <div class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-emerald-500/5 rounded-full group-hover:scale-125 transition-transform"></div>
            </div>
        </div>

        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Enrollment Growth Chart -->
            <div class="glass rounded-3xl p-4 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--text-primary);">Enrollment Growth</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Student intake over the last 6 months</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#0B4D73]"></div>
                        <span class="text-xs font-bold" style="color: var(--text-secondary);">New Students</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>

            <!-- Program Distribution Chart -->
            <div class="glass rounded-3xl p-4 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--text-primary);">Programme Types</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Distribution of rolling vs scheduled courses</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
                    <div class="chart-container" style="height: 240px;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl border transition-colors bg-opacity-50" style="background-color: var(--bg-primary); border-color: var(--border-color);">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-2 h-2 rounded-full bg-[#0B4D73]"></div>
                                <span class="text-sm font-bold" style="color: var(--text-primary);">Rolling (Age-based)</span>
                            </div>
                            <p class="text-2xl font-black text-[#0B4D73]">{{ $programDistribution['rolling'] }}</p>
                        </div>
                        <div class="p-4 rounded-2xl border transition-colors bg-opacity-50" style="background-color: var(--bg-primary); border-color: var(--border-color);">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="w-2 h-2 rounded-full bg-blue-300"></div>
                                <span class="text-sm font-bold" style="color: var(--text-primary);">Scheduled (Cohort)</span>
                            </div>
                            <p class="text-2xl font-black text-blue-400">{{ $programDistribution['scheduled'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Data Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
            <!-- Recent Students -->
            <div class="lg:col-span-2 space-y-8">
                <div class="admin-card overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold" style="color: var(--text-primary);">Recent Enrollments</h3>
                            <p class="text-xs" style="color: var(--text-secondary);">Latest children added to the system</p>
                        </div>
                        <a href="{{ route('admin.children.index') }}"
                            class="text-sm font-bold text-[#0B4D73] hover:underline">View All</a>
                    </div>
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr>
                                    <th class="admin-table-header">Student</th>
                                    <th class="admin-table-header">Programme</th>
                                    <th class="admin-table-header">Joined</th>
                                    <th class="admin-table-header">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--border-color);">
                                @foreach($recentStudents as $student)
                                    <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                                        <td class="px-6 py-4" style="background-color: transparent;">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-bold text-sm">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold" style="color: var(--text-primary);">{{ $student->first_name }}
                                                        {{ $student->last_name }}</p>
                                                    <p class="text-[11px] font-medium" style="color: var(--text-secondary);">
                                                        {{ $student->unique_number ?? 'UNREG-' . $student->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4" style="background-color: transparent;">
                                            @if($student->enrollments->count() > 0 && $student->enrollments->first()->program)
                                                <span
                                                    class="text-xs font-semibold" style="color: var(--text-secondary);">{{ $student->enrollments->first()->program->name }}</span>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-amber-500 bg-amber-500/10 px-2 py-1 rounded-lg">Waitlisted</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium" style="color: var(--text-secondary); background-color: transparent;">
                                            {{ $student->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4" style="background-color: transparent;">
                                            <span
                                                class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Active</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile List -->
                    <div class="md:hidden p-4 space-y-3">
                        @foreach($recentStudents as $student)
                            <div class="flex items-center gap-3 pb-3 border-b last:border-0" style="border-color: var(--border-color);">
                                <div class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black text-xs shrink-0">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-sm font-black truncate" style="color: var(--text-primary);">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </h4>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded uppercase bg-emerald-500/10 text-emerald-500">Active</span>
                                    </div>
                                    <p class="text-[10px] truncate opacity-70 mb-1" style="color: var(--text-secondary);">
                                        {{ $student->enrollments->count() > 0 && $student->enrollments->first()->program ? $student->enrollments->first()->program->name : 'Waitlisted' }}
                                    </p>
                                    <p class="text-[9px] font-bold" style="color: var(--text-secondary);">
                                        Joined {{ $student->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="glass rounded-3xl p-4 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold" style="color: var(--text-primary);">Upcoming Events</h3>
                            <p class="text-sm" style="color: var(--text-secondary);">Scheduled sessions and activities</p>
                        </div>
                        <a href="{{ route('admin.events.index') }}" class="text-sm font-bold text-[#0B4D73] hover:underline">Manage Events</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($upcomingEvents as $event)
                        <div class="p-4 rounded-2xl border transition-all group" style="background-color: var(--bg-primary); border-color: var(--border-color);">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex flex-col items-center justify-center">
                                    <span class="text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                                    <span class="text-lg font-black">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold line-clamp-1" style="color: var(--text-primary);">{{ $event->title }}</h4>
                                    <p class="text-[10px] font-medium" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</p>
                                </div>
                            </div>
                            <p class="text-xs line-clamp-2 mb-4 h-8" style="color: var(--text-secondary);">{{ $event->description ?? 'No description provided.' }}</p>
                            <div class="flex items-center gap-2 text-[10px] font-bold" style="color: var(--text-secondary);">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location ?? 'Main Hall' }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-3 py-12 text-center bg-opacity-5 rounded-2xl border border-dashed" style="background-color: var(--text-primary); border-color: var(--border-color);">
                            <i class="fas fa-calendar-alt text-3xl text-slate-400 mb-3 opacity-20"></i>
                            <p class="text-sm font-bold uppercase tracking-widest opacity-40" style="color: var(--text-primary);">No upcoming events</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Notifications Feed -->
            <div class="glass rounded-3xl p-6 shadow-sm flex flex-col h-fit">
                <div class="flex items-center justify-between mb-8 px-2">
                    <h3 class="text-xl font-bold" style="color: var(--text-primary);">Notifications</h3>
                    <span class="px-2 py-1 bg-red-500/10 text-red-500 text-[10px] font-black rounded-lg">{{ $notifications->count() }} NEW</span>
                </div>
                
                <div class="space-y-6">
                    @forelse($notifications as $notification)
                    <div class="flex gap-4 relative group">
                        <div class="flex-shrink-0">
                            @php
                                $iconStyle = 'color: #3b82f6; background: rgba(59, 130, 246, 0.1);';
                                $icon = 'fa-bell';
                                if(($notification->data['type'] ?? '') === 'birthday') {
                                    $iconStyle = 'color: #ec4899; background: rgba(236, 72, 153, 0.1);';
                                    $icon = 'fa-birthday-cake';
                                }
                            @endphp
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm shadow-sm group-hover:scale-110 transition-transform" style="{{ $iconStyle }}">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <p class="text-[10px] font-black uppercase tracking-widest" style="color: var(--text-secondary);">
                                    {{ ($notification->data['type'] ?? 'Alert') }}
                                </p>
                                <span class="text-[10px] font-bold uppercase" style="color: var(--text-secondary);">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                            </div>
                            <p class="text-sm leading-snug" style="color: var(--text-primary);">
                                {{ $notification->data['message'] ?? 'New system alert' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 opacity-50">
                        <i class="fas fa-check-circle text-4xl mb-4 text-slate-400 opacity-20"></i>
                        <p class="text-sm font-bold uppercase tracking-widest" style="color: var(--text-primary);">All caught up!</p>
                    </div>
                @endforelse
                </div>

                <a href="{{ route('admin.notifications.index') }}" class="mt-8 py-3 w-full text-center text-xs font-bold text-[#0B4D73] bg-[#0B4D73]/10 rounded-xl hover:bg-[#0B4D73]/20 transition-colors">
                    View All Notifications
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get root styles for Chart.js
            const rootStyle = getComputedStyle(document.documentElement);
            const gridColor = rootStyle.getPropertyValue('--border-color').trim() || 'rgba(0,0,0,0.1)';
            const textColor = rootStyle.getPropertyValue('--text-secondary').trim() || '#64748b';
            const primaryColor = rootStyle.getPropertyValue('--primary-500').trim() || '#0B4D73';
            const accentColor = rootStyle.getPropertyValue('--primary-light') ? '#bae6fd' : '#bae6fd'; // Fallback

            // Shared Chart Configuration
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
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
            };

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentGradient = enrollmentCtx.createLinearGradient(0, 0, 0, 300);
            enrollmentGradient.addColorStop(0, primaryColor + '66'); // 40% opacity
            enrollmentGradient.addColorStop(1, primaryColor + '00'); // Transparent

            new Chart(enrollmentCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($enrollmentTrends, 'month')) !!},
                    datasets: [{
                        label: 'Enrollments',
                        data: {!! json_encode(array_column($enrollmentTrends, 'count')) !!},
                        borderColor: primaryColor,
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: enrollmentGradient
                    }]
                },
                options: chartOptions
            });

            // Distribution Chart
            const distributionCtx = document.getElementById('distributionChart').getContext('2d');
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Rolling', 'Scheduled'],
                    datasets: [{
                        data: [{{ $programDistribution['rolling'] }}, {{ $programDistribution['scheduled'] }}],
                        backgroundColor: [primaryColor, '#bae6fd'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
@endsection