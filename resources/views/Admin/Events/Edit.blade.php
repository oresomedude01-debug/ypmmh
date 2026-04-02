@extends('layouts.dashboard')

@section('title', 'Edit Event')

@section('content')
    <div class="max-w-3xl mx-auto animate-fade-in">
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm font-medium text-slate-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[#0B4D73]">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-[10px]"></i></li>
                <li><a href="{{ route('admin.events.index') }}" class="hover:text-[#0B4D73]">Events</a></li>
                <li><i class="fas fa-chevron-right text-[10px]"></i></li>
                <li class="text-slate-900">Edit Event</li>
            </ol>
        </nav>

        <div class="glass rounded-3xl overflow-hidden shadow-lg border border-white/50">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-2xl font-black text-slate-900">Edit Event</h1>
                <p class="text-sm text-slate-500 font-medium">Update the details for "{{ $event->title }}".</p>
            </div>

            <form action="{{ route('admin.events.update', $event) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Event
                            Title</label>
                        <input type="text" name="title" required value="{{ old('title', $event->title) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Event
                            Category</label>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                            <option value="general" {{ $event->type == 'general' ? 'selected' : '' }}>General</option>
                            <option value="session" {{ $event->type == 'session' ? 'selected' : '' }}>Session</option>
                            <option value="workshop" {{ $event->type == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="holiday" {{ $event->type == 'holiday' ? 'selected' : '' }}>Holiday</option>
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Start Date &
                            Time</label>
                        <input type="datetime-local" name="start_time" required
                            value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">End Date &
                            Time (Optional)</label>
                        <input type="datetime-local" name="end_time"
                            value="{{ old('end_time', $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Description /
                            Notes</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-[#0B4D73] transition-all font-medium">{{ old('description', $event->description) }}</textarea>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-6 py-3 font-bold text-slate-500 hover:text-slate-700 transition-colors">
                        Discard
                    </a>
                    <button type="submit" class="btn btn-primary px-8 py-3">
                        <i class="fas fa-save mr-2"></i>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection