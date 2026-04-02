@extends('layouts.dashboard')

@section('title', 'Events')

@section('styles')
    <style>
        .event-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-tap-highlight-color: transparent;
        }

        .event-card:active {
            transform: scale(0.98);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #6366f1 100%);
        }

        .ticket-edge {
            position: relative;
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
                    <h1 class="text-2xl font-black tracking-tight mb-0.5">Workshops</h1>
                    <p class="text-indigo-100/70 text-[10px] font-black uppercase tracking-[0.2em]">Live Gatherings</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl shadow-inner border border-white/20">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-4">
            @forelse($events as $event)
                <div
                    class="event-card bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 relative overflow-hidden group active:bg-slate-50">
                    <div class="flex flex-col md:flex-row gap-5">

                        <!-- Date Pass Style -->
                        <div
                            class="w-full md:w-24 h-24 md:h-auto bg-[#0B4D73] rounded-2xl flex flex-col items-center justify-center text-white shrink-0 shadow-lg shadow-blue-900/10">
                            <span
                                class="text-[8px] font-black uppercase tracking-widest opacity-60">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                            <span
                                class="text-3xl font-black leading-none my-1">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                            <span
                                class="text-[8px] font-black uppercase tracking-tighter opacity-60">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                        </div>

                        <!-- Ticket Details -->
                        <div class="flex-1 min-w-0">
                            <div
                                class="inline-flex items-center gap-2 px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md text-[8px] font-black uppercase tracking-widest mb-2 border border-indigo-100">
                                {{ $event->type }}
                            </div>
                            <h3 class="text-lg font-black text-slate-900 truncate mb-1">{{ $event->title }}</h3>
                            <p class="text-xs text-slate-500 font-medium line-clamp-2 mb-4">{{ $event->description }}</p>

                            @if($event->location)
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i>
                                    <span class="text-[10px] font-bold truncate">{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Secondary Action -->
                        <div class="flex items-center justify-center pt-4 md:pt-0 md:pl-4 md:border-l border-slate-50">
                            <button
                                class="w-full md:w-auto px-6 py-3 bg-slate-50 text-[#0B4D73] rounded-2xl text-[9px] font-black uppercase tracking-widest border border-slate-100 active:bg-slate-100 transition-colors">
                                Remind
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center bg-white rounded-[3rem] border border-slate-100/80 shadow-sm">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="fas fa-calendar-times text-3xl text-slate-200"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-1 tracking-tight">No Events... Yet</h3>
                    <p class="text-xs text-slate-500 font-medium">Check back soon for upcoming workshops!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection