@extends('layouts.dashboard')

@section('title', 'My Notifications')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-10">
        <!-- Header -->
        <div class="flex items-center justify-between px-1">
            <div>
                <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#0B4D73] to-blue-400 flex items-center justify-center">
                        <i class="fas fa-bell text-white text-base"></i>
                    </span>
                    My Alerts
                </h1>
                <p class="text-xs text-slate-400 mt-1 ml-13">Tap a notification to go to the relevant page.</p>
            </div>
            <form method="POST" action="{{ route('child.notifications.read-all') }}">
                @csrf
                <button type="submit"
                    class="text-[10px] font-black uppercase text-[#0B4D73] tracking-widest hover:underline flex items-center gap-1">
                    <i class="fas fa-check-double text-[9px]"></i> Mark all read
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-2">
            @forelse($notifications as $notification)
                @php
                    $isUnread = !$notification->read_at;
                    $type = $notification->data['type'] ?? 'system';
                    $redirectUrl = route('child.notifications.redirect', $notification->id);

                    $iconMap = [
                        'new_program_available'  => ['fa-graduation-cap', 'from-emerald-400 to-emerald-600'],
                        'program_update'         => ['fa-sync-alt',       'from-sky-400 to-sky-600'],
                        'lesson_completion'      => ['fa-check-circle',   'from-green-400 to-green-600'],
                        'blog_published'         => ['fa-newspaper',      'from-purple-400 to-purple-600'],
                        'blog_post'              => ['fa-newspaper',      'from-indigo-400 to-indigo-600'],
                        'birthday'               => ['fa-birthday-cake',  'from-pink-400 to-pink-600'],
                    ];
                    [$iconClass, $gradient] = $iconMap[$type] ?? ['fa-bell', 'from-[#0B4D73] to-blue-500'];

                    $labelMap = [
                        'new_program_available'  => 'New Course',
                        'program_update'         => 'Program Update',
                        'lesson_completion'      => 'Achievement',
                        'blog_published'         => 'New Article',
                        'blog_post'              => 'Blog Alert',
                        'birthday'               => 'Birthday',
                    ];
                    $label = $labelMap[$type] ?? 'System';
                @endphp

                <a href="{{ $redirectUrl }}"
                   class="group flex items-start gap-4 p-4 rounded-2xl border transition-all duration-200 cursor-pointer
                          {{ $isUnread
                              ? 'bg-blue-50/70 border-blue-200 shadow-sm'
                              : 'bg-white border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200' }}">

                    <!-- Icon -->
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas {{ $iconClass }} text-white text-sm"></i>
                    </div>

                    <!-- Text -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</span>
                            <span class="text-[10px] text-slate-400 shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm font-bold text-slate-800 leading-snug group-hover:text-[#0B4D73] transition-colors">
                            {{ $notification->data['message'] ?? 'New alert.' }}
                        </p>
                        <span class="mt-1 text-[10px] font-black uppercase tracking-wider text-[#0B4D73] opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                            <i class="fas fa-arrow-right text-[9px]"></i>
                            {{ $isUnread ? 'Mark as read & view' : 'View details' }}
                        </span>
                    </div>

                    <!-- Unread indicator -->
                    @if($isUnread)
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0 mt-2 ring-2 ring-white"></div>
                    @endif
                </a>
            @empty
                <div class="bg-white rounded-[2rem] py-20 text-center border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-2xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-400">All caught up!</h3>
                    <p class="text-slate-400 text-xs mt-1">No notifications right now.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="pt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection