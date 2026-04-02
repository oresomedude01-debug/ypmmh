@extends('layouts.dashboard')

@section('title', 'Reports & Feedback')

@section('content')
    <div class="space-y-6 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Reports & Feedback
                </h1>
                <p class="font-medium" style="color: var(--text-secondary);">Manage user-submitted reports and feedback.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="admin-card p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--text-secondary);">
                        Total Reports</p>
                    <h3 class="text-3xl font-black" style="color: var(--text-primary);">{{ $stats['total'] }}</h3>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-[#0B4D73]/5 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-flag text-4xl opacity-10 text-[#0B4D73]"></i>
                </div>
            </div>

            <div class="admin-card p-6 relative overflow-hidden group border-l-4 border-l-amber-500">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--text-secondary);">
                        Pending</p>
                    <h3 class="text-3xl font-black text-amber-600">{{ $stats['pending'] }}</h3>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-4xl opacity-10 text-amber-500"></i>
                </div>
            </div>

            <div class="admin-card p-6 relative overflow-hidden group border-l-4 border-l-emerald-500">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--text-secondary);">
                        Resolved</p>
                    <h3 class="text-3xl font-black text-emerald-600">{{ $stats['resolved'] }}</h3>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-4xl opacity-10 text-emerald-500"></i>
                </div>
            </div>

            <div class="admin-card p-6 relative overflow-hidden group border-l-4 border-l-rose-500">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: var(--text-secondary);">
                        Dismissed</p>
                    <h3 class="text-3xl font-black text-rose-600">{{ $stats['dismissed'] }}</h3>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/5 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-times-circle text-4xl opacity-10 text-rose-500"></i>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="admin-table-header rounded-tl-3xl">Reporter</th>
                            <th class="admin-table-header">Type/Target</th>
                            <th class="admin-table-header">Reason</th>
                            <th class="admin-table-header">Status</th>
                            <th class="admin-table-header">Date</th>
                            <th class="admin-table-header text-right rounded-tr-3xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border-color);">
                        @forelse($reports as $report)
                            <tr class="hover:bg-opacity-5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black text-xs">
                                            {{ substr($report->reporter->first_name, 0, 1) }}{{ substr($report->reporter->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold leading-tight" style="color: var(--text-primary);">
                                                {{ $report->reporter->full_name }}
                                            </p>
                                            <p class="text-[10px]" style="color: var(--text-secondary);">
                                                {{ $report->reporter->roles->first()->name ?? 'User' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold" style="color: var(--text-primary);">
                                        {{ class_basename($report->reportable_type) }}
                                    </span>
                                    <p class="text-[10px]" style="color: var(--text-secondary);">ID:
                                        {{ $report->reportable_id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs line-clamp-1 max-w-xs" style="color: var(--text-secondary);">
                                        {{ $report->reason }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                            'resolved' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                            'dismissed' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $statusColors[$report->status] ?? '' }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium" style="color: var(--text-secondary);">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.reports.show', $report) }}"
                                            class="p-2 bg-blue-500/10 text-blue-600 rounded-lg hover:bg-blue-500/20 transition-all">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.reports.destroy', $report) }}" method="POST"
                                            onsubmit="return confirm('Delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="p-2 bg-red-500/10 text-red-600 rounded-lg hover:bg-red-500/20 transition-all">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3 opacity-20">
                                        <i class="fas fa-clipboard-list text-5xl mb-2"></i>
                                        <h4 class="text-xl font-black uppercase tracking-widest text-[#0B4D73]">No reports found
                                        </h4>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reports->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection