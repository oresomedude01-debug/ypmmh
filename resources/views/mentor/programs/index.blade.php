@extends('layouts.dashboard')

@section('title', 'My Programmes')

@section('styles')
    <style>
        .analytics-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
        }

        .analytics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px var(--shadow-color);
            border-color: var(--primary-500);
        }

        /* Themed utility overrides */
        [data-theme="dark"] .stat-icon-bg {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                My Programmes
            </h1>
            <p class="font-medium" style="color: var(--text-secondary);">
                View and manage the programmes assigned to you.
            </p>
        </div>
        <!-- View Toggle -->
        <div class="hidden lg:flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
            <button onclick="setViewMode('table')" id="table-view-btn"
                class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
                <i class="fas fa-list"></i>
                <span>Table</span>
            </button>
            <button onclick="setViewMode('grid')" id="grid-view-btn"
                class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
                <i class="fas fa-th-large"></i>
                <span>Grid</span>
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 animate-in fade-in slide-in-from-top">
            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
            <p class="text-emerald-700 font-semibold">{{ session('success') }}</p>
            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Total Programmes</p>
                <h3 class="text-2xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ $programs->count() }}</h3>
            </div>
        </div>

        <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl stat-icon-bg bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
                <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Total Contents</p>
                <h3 class="text-2xl sm:text-3xl font-black text-purple-500">{{ $programs->sum('contents_count') }}</h3>
            </div>
        </div>

        <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl stat-icon-bg bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Total Enrollments</p>
                <h3 class="text-2xl sm:text-3xl font-black text-emerald-500">{{ $programs->sum('enrollments_count') }}</h3>
            </div>
        </div>
    </div>

    <!-- Programs View Container -->
    @if($programs->count() > 0)
        <!-- Grid View -->
        <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($programs as $program)
                <div class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                    <!-- Program Header -->
                    <div class="p-6 border-b border-slate-100 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#0B4D73] to-blue-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-900/20">
                                <i class="fas {{ $program->type === 'rolling' ? 'fa-sync-alt' : 'fa-calendar-check' }}"></i>
                            </div>
                            @php
                                $statusColors = [
                                    'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'draft' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'archived' => 'bg-slate-100 text-slate-700 border-slate-200'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusColors[$program->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $program->status }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-[#0B4D73] transition-colors line-clamp-1">
                            {{ $program->name }}
                        </h3>
                        <p class="text-sm text-slate-500 line-clamp-2">
                            {{ $program->description ?? 'No description provided.' }}
                        </p>
                    </div>

                    <!-- Program Stats -->
                    <div class="px-6 py-4 bg-slate-50/50 grid grid-cols-3 gap-4 text-center border-t border-slate-100">
                        <div>
                            <p class="text-xl font-black text-[#0B4D73]">{{ $program->contents_count }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Contents</p>
                        </div>
                        <div>
                            <p class="text-xl font-black text-[#0B4D73]">{{ $program->enrollments_count }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Enrolled</p>
                        </div>
                        <div>
                            <p class="text-xs font-black text-[#0B4D73] mt-1">{{ ucfirst($program->type) }}</p>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Type</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 border-t border-slate-100">
                        <a href="{{ route('mentor.programs.show', $program) }}" 
                           class="btn btn-primary w-full justify-center text-xs py-3">
                            <i class="fas fa-eye mr-2"></i>View & Manage
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div id="table-view" class="hidden overflow-x-auto glass rounded-3xl border border-slate-100/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Programme</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Type</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Stats</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($programs as $program)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-[#0B4D73] flex items-center justify-center text-lg group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
                                        <i class="fas {{ $program->type === 'rolling' ? 'fa-sync-alt' : 'fa-calendar-check' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-[#0B4D73]">{{ $program->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium">SYS-{{ str_pad($program->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusColors[$program->status] ?? 'bg-slate-50 text-slate-400' }}">
                                    {{ $program->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest">
                                    {{ $program->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-4">
                                    <div class="text-center">
                                        <p class="text-xs font-black text-slate-900 leading-tight">{{ $program->enrollments_count }}</p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Students</p>
                                    </div>
                                    <div class="text-center border-l border-slate-100 pl-4">
                                        <p class="text-xs font-black text-slate-900 leading-tight">{{ $program->contents_count ?? 0 }}</p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Contents</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('mentor.programs.show', $program) }}" class="p-2 text-slate-400 hover:text-[#0B4D73] hover:bg-blue-50 rounded-lg transition-all inline-block" title="Manage">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="glass rounded-3xl p-16 text-center border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-book-open text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">No Programmes Assigned</h3>
            <p class="text-slate-500 max-w-sm mx-auto">
                You don't have any programmes assigned to you yet. Contact an administrator to get assigned to a programme.
            </p>
        </div>
    @endif

    <script>
        function setViewMode(mode) {
            const gridView = document.getElementById('grid-view');
            const tableView = document.getElementById('table-view');
            const gridBtn = document.getElementById('grid-view-btn');
            const tableBtn = document.getElementById('table-view-btn');

            if (!gridView || !tableView) return;

            if (mode === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                gridBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                gridBtn.classList.remove('text-slate-500');
                tableBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                tableBtn.classList.add('text-slate-500');
            } else {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
                tableBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                tableBtn.classList.remove('text-slate-500');
                gridBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
                gridBtn.classList.add('text-slate-500');
            }
            localStorage.setItem('mentor_programs_view', mode);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedMode = localStorage.getItem('mentor_programs_view') || 'table';
            setViewMode(savedMode);
        });
    </script>
</div>
@endsection

