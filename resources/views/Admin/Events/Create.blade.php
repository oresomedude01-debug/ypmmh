@extends('layouts.dashboard')

@section('title', 'Schedule New Event')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#0B4D73]">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.events.index') }}" class="hover:text-[#0B4D73]">Events</a></li>
                <li><i class="fas fa-chevron-right text-[10px]"></i></li>
                <li class="text-slate-900">Schedule New</li>
            </ol>
        </nav>

        <div class="glass rounded-3xl overflow-hidden shadow-lg border border-white/50">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-2xl font-black text-slate-900">Schedule New Event</h1>
                <p class="text-sm text-slate-500 font-medium">Define dates, locations, and details for your next session.
                </p>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Event
                            Title</label>
                        <input type="text" name="title" required placeholder="e.g., Parent-Teacher Meeting"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Event
                            Category</label>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                            <option value="general">General</option>
                            <option value="session">Session</option>
                            <option value="workshop">Workshop</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Location</label>
                        <input type="text" name="location" placeholder="e.g., Main Hall"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Start Date &
                            Time</label>
                        <input type="datetime-local" name="start_time" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">End Date &
                            Time (Optional)</label>
                        <input type="datetime-local" name="end_time"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Description /
                            Notes</label>
                        <textarea name="description" rows="4" placeholder="Briefly describe what this event is about..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium"></textarea>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-6 py-3 font-bold text-slate-500 hover:text-slate-700 transition-colors">
                        Discard
                    </a>
                    <button type="submit" class="btn btn-primary px-8 py-3">
                        <i class="fas fa-check mr-2"></i>
                        Schedule Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection