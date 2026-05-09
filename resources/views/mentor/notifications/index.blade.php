@extends('layouts.dashboard')

@section('title', 'My Notifications')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Notification Inbox
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Keep track of program milestones and system alerts.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('mentor.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary glass text-sm">
                        <i class="fas fa-check-double"></i>
                        <span>Mark All as Read</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Inbox -->
        <div class="glass rounded-3xl overflow-hidden shadow-sm flex flex-col">
            <div class="p-4 border-b bg-opacity-5 flex items-center justify-between font-bold px-8"
                style="border-color: var(--border-color); background-color: var(--text-primary);">
                <h3 class="text-sm" style="color: var(--text-primary);">Recent Alerts</h3>
            </div>

            <div class="divide-y" style="border-color: var(--border-color);">
                @forelse($notifications as $notification)
                    @php
                        $isUnread = $notification->unread();
                        $type = $notification->data['type'] ?? 'system';
                        $redirectUrl = route('mentor.notifications.redirect', $notification->id);

                        $iconMap = [
                            'new_program_available'  => ['fa-graduation-cap', 'color:#10b981;background:rgba(16,185,129,0.1);'],
                            'program_update'         => ['fa-sync-alt',       'color:#0ea5e9;background:rgba(14,165,233,0.1);'],
                            'blog_post'              => ['fa-newspaper',       'color:#6366f1;background:rgba(99,102,241,0.1);'],
                            'new_blog_post'          => ['fa-newspaper',       'color:#6366f1;background:rgba(99,102,241,0.1);'],
                            'blog_published'         => ['fa-newspaper',       'color:#8b5cf6;background:rgba(139,92,246,0.1);'],
                            'birthday'               => ['fa-birthday-cake',   'color:#ec4899;background:rgba(236,72,153,0.1);'],
                        ];
                        [$icon, $iconStyle] = $iconMap[$type] ?? ['fa-bell', 'color:#3b82f6;background:rgba(59,130,246,0.1);'];

                        $labelMap = [
                            'new_program_available'  => 'New Course',
                            'program_update'         => 'Program Update',
                            'blog_post'              => 'Blog Alert',
                            'new_blog_post'          => 'Blog Alert',
                            'blog_published'         => 'Blog Published',
                            'birthday'               => 'Birthday Alert',
                        ];
                        $label = $labelMap[$type] ?? 'System Alert';
                    @endphp

                    <a href="{{ $redirectUrl }}"
                       class="group block transition-all duration-200 {{ $isUnread ? 'bg-opacity-5 border-l-4' : 'hover:bg-opacity-5' }}"
                       style="{{ $isUnread ? 'background-color: var(--primary-500); border-color: var(--primary-500);' : '' }}"
                       title="Click to view and mark as read">
                        <div class="p-6 flex items-start gap-4">
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
                                        <span class="text-[10px] font-black uppercase tracking-widest" style="color: var(--text-secondary);">
                                            {{ $label }}
                                        </span>
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] font-bold shrink-0 whitespace-nowrap" style="color: var(--text-secondary);">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h4 class="text-sm font-bold leading-snug" style="color: var(--text-primary);">
                                    {{ $notification->data['message'] ?? 'New system alert' }}
                                </h4>

                                <div class="mt-3 flex items-center gap-2 text-[11px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity"
                                     style="color: var(--primary-500);">
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                    {{ $isUnread ? 'Mark as read & view' : 'View details' }}
                                </div>
                            </div>

                            <!-- Delete (stop propagation so it doesn't trigger the link) -->
                            <form action="{{ route('mentor.notifications.destroy', $notification->id) }}" method="POST"
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
                            <i class="fas fa-envelope-open text-5xl mb-4"></i>
                            <p class="text-sm font-bold uppercase tracking-widest" style="color: var(--text-primary);">Inbox is empty</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="p-6 border-t bg-opacity-5" style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection