@extends('layouts.dashboard')

@section('title', 'System Notifications')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Notification Inbox
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Keep track of student milestones and system alerts.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary glass text-sm">
                        <i class="fas fa-check-double"></i>
                        <span>Mark All as Read</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Inbox -->
        <div class="admin-card overflow-hidden flex flex-col">
            <div class="px-8 py-4 admin-table-header flex items-center justify-between">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-white">Recent Alerts</h3>
                <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-black">{{ $notifications->total() }} TOTAL</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    @php
                        $isUnread = $notification->unread();
                        $type = $notification->data['type'] ?? 'system';
                        $redirectUrl = route('admin.notifications.redirect', $notification->id);

                        // Icon & colour map
                        $iconMap = [
                            'new_user_registration'  => ['fa-user-plus',      'color:#0B4D73;background:rgba(11,77,115,0.1);'],
                            'new_program_available'  => ['fa-graduation-cap', 'color:#10b981;background:rgba(16,185,129,0.1);'],
                            'blog_post'              => ['fa-newspaper',       'color:#6366f1;background:rgba(99,102,241,0.1);'],
                            'new_blog_post'          => ['fa-newspaper',       'color:#6366f1;background:rgba(99,102,241,0.1);'],
                            'blog_published'         => ['fa-newspaper',       'color:#8b5cf6;background:rgba(139,92,246,0.1);'],
                            'report'                 => ['fa-flag',            'color:#e11d48;background:rgba(225,29,72,0.1);'],
                            'birthday'               => ['fa-birthday-cake',   'color:#ec4899;background:rgba(236,72,153,0.1);'],
                        ];
                        [$icon, $iconStyle] = $iconMap[$type] ?? ['fa-bell', 'color:#0B4D73;background:rgba(11,77,115,0.1);'];

                        $labelMap = [
                            'new_user_registration'  => 'New Registration',
                            'new_program_available'  => 'New Course',
                            'blog_post'              => 'Blog Alert',
                            'new_blog_post'          => 'Blog Alert',
                            'blog_published'         => 'Blog Published',
                            'report'                 => 'Content Report',
                            'birthday'               => 'Birthday Alert',
                        ];
                        $label = $labelMap[$type] ?? 'System Alert';
                    @endphp

                    <a href="{{ $redirectUrl }}"
                       class="group block transition-all duration-200 hover:bg-slate-50 cursor-pointer
                              {{ $isUnread ? 'bg-blue-50/60 border-l-4 border-l-[#0B4D73]' : '' }}"
                       title="Click to view and mark as read">
                        <div class="p-5 flex items-start gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform"
                                     style="{{ $iconStyle }}">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</span>
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 shrink-0 whitespace-nowrap">
                                        <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm font-bold leading-snug text-slate-800 group-hover:text-[#0B4D73] transition-colors">
                                    {{ $notification->data['message'] ?? 'New system alert' }}
                                </p>

                                <div class="mt-3 flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-[#0B4D73] opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                    {{ $isUnread ? 'Mark as read & view' : 'View details' }}
                                </div>
                            </div>

                            <!-- Delete button (separate from the link) -->
                            <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                                  onsubmit="event.stopPropagation(); return confirm('Delete this notification?');"
                                  onclick="event.preventDefault(); event.stopPropagation(); if(confirm('Delete this notification?')) this.submit();">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-lg text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all opacity-0 group-hover:opacity-100"
                                    title="Delete notification">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                @empty
                    <div class="py-24 text-center">
                        <div class="flex flex-col items-center opacity-20">
                            <i class="fas fa-envelope-open text-5xl mb-4 text-[#0B4D73]"></i>
                            <p class="text-sm font-bold uppercase tracking-widest text-[#0B4D73]">Inbox is empty</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="p-6 border-t bg-slate-50 border-slate-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection