@extends('layouts.dashboard')

@section('title', 'Events')

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Events Calendar
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Stay updated with upcoming madrasah sessions,
                    workshops, and holidays.</p>
            </div>
        </div>

        <!-- Events List -->
        <div class="glass rounded-3xl overflow-hidden shadow-sm flex flex-col">
            <div class="p-6 border-b bg-opacity-5 flex items-center justify-between font-bold px-8"
                style="border-color: var(--border-color); background-color: var(--text-primary);">
                <h3 style="color: var(--text-primary);">Upcoming Events</h3>
                <span class="text-xs" style="color: var(--text-secondary);">{{ $events->total() }} Total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-widest"
                            style="background-color: var(--bg-secondary); color: var(--text-secondary);">
                            <th class="px-6 py-4" style="background: transparent;">Event Date</th>
                            <th class="px-6 py-4" style="background: transparent;">Title & Description</th>
                            <th class="px-6 py-4" style="background: transparent;">Location</th>
                            <th class="px-6 py-4" style="background: transparent;">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border-color);">
                        @forelse($events as $event)
                            <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                                <td class="px-6 py-4" style="background-color: transparent;">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex flex-col items-center justify-center border border-[#0B4D73]/20">
                                            <span
                                                class="text-[10px] font-bold uppercase leading-none">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                                            <span
                                                class="text-lg font-black leading-none mt-1">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                                        </div>
                                        <div class="text-[11px] font-bold italic" style="color: var(--text-secondary);">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-md" style="background-color: transparent;">
                                    <p class="text-sm font-bold mb-1" style="color: var(--text-primary);">{{ $event->title }}
                                    </p>
                                    <p class="text-xs line-clamp-1" style="color: var(--text-secondary);">
                                        {{ $event->description ?? 'No description.' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4" style="background-color: transparent;">
                                    <div class="flex items-center gap-2 text-xs font-semibold"
                                        style="color: var(--text-secondary);">
                                        <i class="fas fa-map-marker-alt opacity-50"></i>
                                        <span>{{ $event->location ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4" style="background-color: transparent;">
                                    @php
                                        $badgeStyle = 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                        if ($event->type == 'holiday')
                                            $badgeStyle = 'bg-red-500/10 text-red-500 border-red-500/20';
                                        elseif ($event->type == 'session')
                                            $badgeStyle = 'bg-blue-500/10 text-blue-500 border-blue-500/20';
                                        elseif ($event->type == 'workshop')
                                            $badgeStyle = 'bg-purple-500/10 text-purple-500 border-purple-500/20';
                                    @endphp
                                    <span
                                        class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-tighter border {{ $badgeStyle }}">
                                        {{ $event->type }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-24 text-center" style="background-color: transparent;">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fas fa-calendar-times text-5xl mb-4"></i>
                                        <p class="text-sm font-bold uppercase tracking-widest"
                                            style="color: var(--text-primary);">No events scheduled</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="p-6 border-t bg-opacity-5"
                    style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection