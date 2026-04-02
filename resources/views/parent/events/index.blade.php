@extends('layouts.dashboard')

@section('title', 'Community Events')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in pb-10">
        <!-- Compact Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-center gap-6 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 opacity-10 shimmer"></div>
            <div class="relative z-10 flex items-center gap-5">
                <div
                    class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30 shadow-inner">
                    <i class="fas fa-calendar-alt text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black mb-1">Community Events</h1>
                    <p class="text-indigo-100 font-bold uppercase tracking-widest text-[10px]">Stay Updated on Gatherings
                    </p>
                </div>
            </div>
            <div class="relative z-10 hidden md:block">
                <span
                    class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-xs font-black uppercase tracking-widest border border-white/20">
                    {{ $events->count() }} Upcoming Events
                </span>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-4">
            @forelse($events as $event)
                <div class="glass rounded-2xl border border-white p-5 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Date Box -->
                        <div
                            class="md:w-32 flex flex-col items-center justify-center bg-slate-50 rounded-xl border border-slate-100 p-4 shrink-0 group-hover:bg-indigo-50 transition-colors">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                            <span
                                class="text-3xl font-black text-slate-900 leading-tight">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                            <span
                                class="text-[10px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($event->start_time)->format('D, H:i') }}</span>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[8px] font-black uppercase tracking-widest rounded-md">
                                    {{ $event->type }}
                                </span>
                                <h3 class="text-lg font-black text-slate-900 truncate">{{ $event->title }}</h3>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $event->description }}</p>

                            <div class="flex flex-wrap items-center gap-4 text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-clock text-[10px]"></i>
                                    <span
                                        class="text-[10px] font-bold">{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                        -
                                        {{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : 'End' }}</span>
                                </div>
                                @if($event->location)
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-[10px]"></i>
                                        <span class="text-[10px] font-bold truncate max-w-[200px]">{{ $event->location }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="md:w-32 flex items-center justify-center shrink-0">
                            <button
                                class="w-full md:w-auto px-6 py-3 bg-[#0B4D73] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-900 transition-all shadow-lg shadow-blue-900/10">
                                Add to Calendar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass rounded-2xl p-20 text-center border-2 border-dashed border-slate-200">
                    <i class="fas fa-calendar-day text-5xl text-slate-200 mb-4 block"></i>
                    <h3 class="text-xl font-black text-slate-400">No events scheduled yet.</h3>
                    <p class="text-slate-500 text-xs">Stay tuned for workshops and community circles!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection