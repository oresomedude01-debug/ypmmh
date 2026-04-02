@extends('layouts.dashboard')

@section('title', 'My Alerts')

@section('styles')
    <style>
        .notification-item {
            transition: all 0.2s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .notification-item:active {
            background-color: #f8fafc;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in pb-20 md:pb-10">

        <!-- Native Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black tracking-tight mb-0.5">Alerts</h1>
                    <p class="text-blue-200/70 text-[10px] font-black uppercase tracking-[0.2em]">Updates & News</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl shadow-inner border border-white/20">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100/80 shadow-sm overflow-hidden">
            @forelse($notifications as $notification)
                <div class="notification-item flex items-start gap-4 p-5 border-b border-slate-50 last:border-0 relative group">
                    <!-- Icon Bubble -->
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm text-white
                                            {{ $notification->data['type'] === 'enrollment' ? 'bg-indigo-500' : '' }}
                                            {{ $notification->data['type'] === 'new_content' ? 'bg-emerald-500' : '' }}
                                            {{ $notification->data['type'] === 'chat' ? 'bg-[#0B4D73]' : '' }}
                                            {{ $notification->data['type'] === 'achievement' ? 'bg-yellow-500' : '' }}
                                            {{ !in_array($notification->data['type'], ['enrollment', 'new_content', 'chat', 'achievement']) ? 'bg-slate-400' : '' }}
                                        ">
                        <i class="fas 
                                                {{ $notification->data['type'] === 'enrollment' ? 'fa-map-signs' : '' }}
                                                {{ $notification->data['type'] === 'new_content' ? 'fa-book-reader' : '' }}
                                                {{ $notification->data['type'] === 'chat' ? 'fa-comments' : '' }}
                                                {{ $notification->data['type'] === 'achievement' ? 'fa-award' : '' }}
                                                {{ !in_array($notification->data['type'], ['enrollment', 'new_content', 'chat', 'achievement']) ? 'fa-bell' : '' }}
                                            text-lg"></i>
                    </div>

                    <div class="flex-1 min-w-0 pr-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-[#0B4D73]/40">
                                {{ str_replace('_', ' ', $notification->data['type']) }}
                            </span>
                            <span class="text-[8px] font-bold text-slate-300">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-slate-800 leading-snug">{{ $notification->data['message'] }}</p>

                        @if(isset($notification->data['program_id']))
                            <div class="mt-2 text-right">
                                <a href="{{ route('child.programs.show', $notification->data['program_id']) }}"
                                    class="inline-flex items-center gap-2 text-[9px] font-black text-[#0B4D73] uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                                    <span>Enter Program</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Delete Swipe-like button -->
                    <form action="{{ route('child.notifications.destroy', $notification->id) }}" method="POST"
                        class="absolute top-5 right-5">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-200 hover:text-red-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-20 text-center">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="fas fa-bell-slash text-3xl text-slate-200"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-1 tracking-tight">All Caught Up!</h3>
                    <p class="text-xs text-slate-500 font-medium">No new alerts found in your journey.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection