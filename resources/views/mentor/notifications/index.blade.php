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
                <p class="font-medium" style="color: var(--text-secondary);">Keep track of program milestones and system
                    alerts.</p>
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
                    <div class="p-6 transition-all group flex items-start gap-4 {{ $notification->unread() ? 'bg-opacity-5 border-l-4' : 'hover:bg-opacity-5' }}"
                        style="{{ $notification->unread() ? 'background-color: var(--primary-500); border-color: var(--primary-500);' : '' }}">
                        <div class="flex-shrink-0">
                            @php
                                $iconStyle = 'color: #3b82f6; background: rgba(59, 130, 246, 0.1);';
                                $icon = 'fa-bell';
                                if (($notification->data['type'] ?? '') === 'birthday') {
                                    $iconStyle = 'color: #ec4899; background: rgba(236, 72, 153, 0.1);';
                                    $icon = 'fa-birthday-cake';
                                }
                            @endphp
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform"
                                style="{{ $iconStyle }}">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest"
                                    style="color: var(--text-secondary);">
                                    {{ $notification->data['type'] ?? 'System' }} alert
                                </span>
                                <span class="text-[11px] font-bold"
                                    style="color: var(--text-secondary);">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-bold leading-snug" style="color: var(--text-primary);">
                                {{ $notification->data['message'] ?? 'New system alert' }}
                            </h4>

                            <div class="mt-4 flex items-center gap-4">
                                @if($notification->unread())
                                    <form action="{{ route('mentor.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-[11px] font-black uppercase tracking-wider hover:underline flex items-center gap-1"
                                            style="color: var(--primary-500);">
                                            <i class="fas fa-check"></i>
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('mentor.notifications.destroy', $notification->id) }}" method="POST"
                                    onsubmit="return confirm('Delete notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[11px] font-black text-red-500 uppercase tracking-wider hover:underline flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-24 text-center">
                        <div class="flex flex-col items-center opacity-20">
                            <i class="fas fa-envelope-open text-5xl mb-4"></i>
                            <p class="text-sm font-bold uppercase tracking-widest" style="color: var(--text-primary);">Inbox is
                                empty</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="p-6 border-t bg-opacity-5"
                    style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection