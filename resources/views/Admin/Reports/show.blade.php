@extends('layouts.dashboard')

@section('title', 'View Report')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}"
                    class="p-2 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-[#0B4D73] transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black" style="color: var(--text-primary);">Report Details</h1>
                    <p class="text-sm font-medium" style="color: var(--text-secondary);">Review and resolve user concerns.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                        'resolved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                        'dismissed' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                    ];
                @endphp
                <span
                    class="px-4 py-1.5 rounded-full text-xs font-black uppercase border {{ $statusColors[$report->status] ?? '' }}">
                    {{ $report->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="md:col-span-2 space-y-6">
                <div class="admin-card p-8">
                    <h3 class="text-xs font-black uppercase tracking-widest text-[#0B4D73] mb-4">Report Reason</h3>
                    <div class="p-6 rounded-2xl italic leading-relaxed"
                        style="background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-primary);">
                        "{{ $report->reason }}"
                    </div>

                    <div class="mt-8 border-t border-slate-100 pt-8">
                        <h3 class="text-xs font-black uppercase tracking-widest text-[#0B4D73] mb-4">Action & Resolution
                        </h3>
                        <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest mb-2"
                                    style="color: var(--text-secondary);">Update
                                    Status</label>
                                <select name="status"
                                    class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 font-medium"
                                    style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending
                                        Review</option>
                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Mark as
                                        Resolved</option>
                                    <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismiss
                                        Report</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest mb-2"
                                    style="color: var(--text-secondary);">Administrative
                                    Notes</label>
                                <textarea name="admin_notes" rows="4"
                                    class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 font-medium resize-none"
                                    style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                                    placeholder="Enter resolution details or internal notes...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-[#0B4D73] text-white rounded-xl font-black uppercase tracking-widest text-xs hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-[#0B4D73]/20">
                                Save resolution
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Reporter Info -->
                <div class="admin-card p-6 border-t-4 border-t-blue-500">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-4" style="color: var(--text-secondary);">
                        Submitted By</h3>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-black text-lg">
                            {{ substr($report->reporter->first_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold leading-tight" style="color: var(--text-primary);">
                                {{ $report->reporter->full_name }}
                            </p>
                            <p class="text-xs" style="color: var(--text-secondary);">
                                {{ $report->reporter->roles->first()->name ?? 'User' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t space-y-2" style="border-color: var(--border-color);">
                        <div class="flex justify-between text-[11px]">
                            <span class="font-bold uppercase tracking-tighter"
                                style="color: var(--text-secondary);">Email</span>
                            <span class="font-medium"
                                style="color: var(--text-primary);">{{ $report->reporter->email }}</span>
                        </div>
                        <div class="flex justify-between text-[11px]">
                            <span class="font-bold uppercase tracking-tighter"
                                style="color: var(--text-secondary);">Date</span>
                            <span class="font-medium"
                                style="color: var(--text-primary);">{{ $report->created_at->format('M d, Y @ h:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Reported Item -->
                <div class="admin-card p-6 border-t-4 border-t-amber-500">
                    <h3 class="text-[10px] font-black uppercase tracking-widest mb-4" style="color: var(--text-secondary);">
                        Reported Content</h3>
                    <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <p class="text-xs font-black text-amber-600 uppercase tracking-widest mb-1">
                            {{ class_basename($report->reportable_type) }}
                        </p>
                        <p class="text-[10px] text-amber-500 font-bold">Internal ID: #{{ $report->reportable_id }}</p>

                        @if($report->reportable instanceof \App\Models\User)
                            <div class="mt-3 text-xs font-bold" style="color: var(--text-primary);">
                                Target User: {{ $report->reportable->full_name }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection