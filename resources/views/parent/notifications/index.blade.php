@extends('layouts.dashboard')

@section('title', 'Family Notifications')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in pb-10">
        <!-- Header -->
        <div class="flex items-center justify-between px-2">
            <div>
                <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                    <i class="fas fa-bell text-[#0B4D73]"></i>
                    Portal Alerts
                </h1>
                <p class="text-xs text-slate-400 mt-1">Tap a notification to go to the relevant page.</p>
            </div>
            <form method="POST" action="{{ route('parent.notifications.read-all') }}">
                @csrf
                <button type="submit"
                    class="text-[10px] font-black uppercase text-[#0B4D73] tracking-widest hover:underline flex items-center gap-1">
                    <i class="fas fa-check-double text-[9px]"></i> Mark all as read
                </button>
            </form>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notif)
                @php
                    $isUnread = !$notif->read_at;
                    $type = $notif->data['type'] ?? 'system';
                    $redirectUrl = route('parent.notifications.redirect', $notif->id);

                    $iconMap = [
                        'new_program_available'  => ['fa-graduation-cap', 'bg-emerald-50 text-emerald-600'],
                        'program_update'         => ['fa-sync-alt',       'bg-sky-50 text-sky-600'],
                        'lesson_completion'      => ['fa-check-circle',   'bg-green-50 text-green-600'],
                        'enrollment_request'     => ['fa-hand-paper',     'bg-amber-50 text-amber-600'],
                        'blog_published'         => ['fa-newspaper',      'bg-purple-50 text-purple-600'],
                        'blog_post'              => ['fa-newspaper',      'bg-indigo-50 text-indigo-600'],
                        'birthday'               => ['fa-birthday-cake',  'bg-pink-50 text-pink-600'],
                    ];
                    [$iconClass, $iconBg] = $iconMap[$type] ?? ['fa-info-circle', 'bg-blue-50 text-[#0B4D73]'];

                    $labelMap = [
                        'new_program_available'  => 'New Course',
                        'program_update'         => 'Program Update',
                        'lesson_completion'      => 'Lesson Complete',
                        'enrollment_request'     => 'Enrollment Request',
                        'blog_published'         => 'New Article',
                        'blog_post'              => 'Blog Alert',
                        'birthday'               => 'Birthday',
                    ];
                    $label = $labelMap[$type] ?? 'Portal Alert';
                @endphp

                <a href="{{ $redirectUrl }}"
                   class="group bg-white rounded-2xl p-5 border transition-all flex items-start gap-4 cursor-pointer
                          {{ $isUnread ? 'border-[#0B4D73]/30 shadow-md' : 'border-slate-100 shadow-sm hover:shadow-md' }}">
                    <!-- Icon -->
                    <div class="w-11 h-11 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fas {{ $iconClass }}"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</span>
                            <span class="text-[10px] text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 mb-1 leading-snug group-hover:text-[#0B4D73] transition-colors">
                            {{ $notif->data['message'] ?? 'New system alert received.' }}
                        </p>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#0B4D73] opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                            <i class="fas fa-arrow-right text-[9px]"></i>
                            {{ $isUnread ? 'Mark as read & view' : 'View details' }}
                        </span>
                    </div>

                    <!-- Unread dot -->
                    @if($isUnread)
                        <div class="w-2.5 h-2.5 rounded-full bg-[#0B4D73] shrink-0 mt-2 unread-dot"></div>
                    @endif
                </a>
            @empty
                <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-slate-200">
                    <i class="fas fa-bell-slash text-5xl text-slate-200 mb-4 block"></i>
                    <h3 class="text-xl font-black text-slate-400">Silence is Golden</h3>
                    <p class="text-slate-500 text-xs">No active alerts at this moment. You're all caught up!</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="pt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection