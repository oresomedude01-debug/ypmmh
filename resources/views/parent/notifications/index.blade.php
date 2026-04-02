@extends('layouts.dashboard')

@section('title', 'Family Notifications')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in pb-10">
        <div class="flex items-center justify-between px-2">
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <i class="fas fa-bell text-[#0B4D73]"></i>
                Portal Alerts
            </h1>
            <button class="text-[10px] font-black uppercase text-[#0B4D73] tracking-widest hover:underline">Mark all as
                read</button>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notif)
                <div
                    class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B4D73] flex items-center justify-center shrink-0">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 mb-1 leading-snug">
                            {{ $notif->data['message'] ?? 'New system alert received.' }}</p>
                        <p class="text-[10px] font-medium text-slate-400 uppercase tracking-tighter">
                            {{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notif->read_at)
                        <div class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-2"></div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-slate-200">
                    <i class="fas fa-bell-slash text-5xl text-slate-200 mb-4 block"></i>
                    <h3 class="text-xl font-black text-slate-400">Silence is Golden</h3>
                    <p class="text-slate-500 text-xs">No active alerts at this moment. You're all caught up!</p>
                </div>
            @endforelse

            <div class="pt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection