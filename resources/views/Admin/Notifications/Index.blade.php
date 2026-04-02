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
                <p class="font-medium" style="color: var(--text-secondary);">Keep track of student milestones and system
                    alerts.</p>
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
                <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-black">{{ $notifications->total() }}
                    TOTAL</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    <div
                        class="p-6 transition-all group flex items-start gap-4 {{ $notification->unread() ? 'bg-blue-50/50 border-l-4 border-l-[#0B4D73]' : 'hover:bg-slate-50' }}">
                        <div class="flex-shrink-0">
                            @php
                                $iconStyle = 'color: #0B4D73; background: rgba(11, 77, 115, 0.1);';
                                $icon = 'fa-bell';
                                if (($notification->data['type'] ?? '') === 'birthday') {
                                    $iconStyle = 'color: #ec4899; background: rgba(236, 72, 153, 0.1);';
                                    $icon = 'fa-birthday-cake';
                                } elseif (($notification->data['type'] ?? '') === 'Report') {
                                    $iconStyle = 'color: #e11d48; background: rgba(225, 29, 72, 0.1);';
                                    $icon = 'fa-flag';
                                }
                            @endphp
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform"
                                style="{{ $iconStyle }}">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    {{ $notification->data['type'] ?? 'System' }} alert
                                </span>
                                <span class="text-[11px] font-bold text-slate-400">
                                    <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold leading-snug text-slate-800">
                                {{ $notification->data['message'] ?? 'New system alert' }}
                            </h4>

                            <div class="mt-4 flex items-center gap-6">
                                @if($notification->unread())
                                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] hover:underline flex items-center gap-1.5">
                                            <i class="fas fa-check-circle"></i>
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                                    onsubmit="return confirm('Delete notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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