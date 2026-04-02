@extends('layouts.dashboard')

@section('title', $program->name)

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
    <div class="max-w-7xl mx-auto space-y-10 animate-fade-in">
        <!-- Success Message -->
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 animate-in fade-in slide-in-from-top">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                <p class="text-emerald-700 font-semibold">{{ session('success') }}</p>
                <button onclick="this.parentElement.style.display='none'"
                    class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1 space-y-3">
                <div class="flex items-center gap-2 text-slate-500 font-bold text-xs uppercase tracking-widest">
                    <a href="{{ route('admin.programs.index') }}"
                        class="hover:text-[#0B4D73] transition-colors">Programmes</a>
                    <i class="fas fa-chevron-right text-[8px]"></i>
                    <span>Management</span>
                </div>
                <h1
                    class="text-3xl md:text-4xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-600 bg-clip-text text-transparent">
                    {{ $program->name }}
                </h1>
                <p class="text-md text-slate-500 leading-relaxed max-w-3xl font-medium">{{ $program->description }}</p>
            </div>
            <div class="flex gap-3 flex-wrap lg:flex-nowrap">
                <a href="{{ route('admin.programs.edit', $program->id) }}"
                    class="btn btn-primary shadow-lg shadow-[#0B4D73]/20">
                    <i class="fas fa-edit mr-2"></i>
                    <span>Edit Programme</span>
                </a>
                <a href="{{ route('admin.programs.index') }}"
                    class="btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>

        <!-- KPIs Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Type -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->type === 'rolling' ? 'fa-sync-alt' : 'fa-calendar-check' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                        style="color: var(--text-secondary);">Programme Type</p>
                    <h3 class="text-2xl font-black capitalize" style="color: var(--text-primary);">{{ $program->type }}</h3>
                </div>
            </div>

            <!-- Status -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-50 text-emerald-500',
                            'draft' => 'bg-amber-50 text-amber-500',
                            'archived' => 'bg-slate-100 text-slate-500'
                        ];
                    @endphp
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl stat-icon-bg {{ $statusColors[$program->status] ?? 'bg-slate-50 text-slate-500' }} flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->status === 'active' ? 'fa-check-circle' : 'fa-info-circle' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                        style="color: var(--text-secondary);">System Status</p>
                    <h3
                        class="text-2xl font-black capitalize {{ Str::contains($statusColors[$program->status] ?? '', 'text-emerald') ? 'text-emerald-500' : (Str::contains($statusColors[$program->status] ?? '', 'text-amber') ? 'text-amber-500' : 'text-slate-500') }}">
                        @if($program->status === 'active') Published @else {{ $program->status }} @endif
                    </h3>
                </div>
            </div>

            <!-- Lead Mentor -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl stat-icon-bg bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                        style="color: var(--text-secondary);">Lead Mentor</p>
                    <h3 class="text-lg font-black truncate" style="color: var(--text-primary);">
                        @if($program->mentor)
                            {{ $program->mentor->first_name }} {{ substr($program->mentor->last_name, 0, 1) }}.
                        @else
                            <span class="text-slate-400 italic font-medium">No Lead</span>
                        @endif
                    </h3>
                </div>
            </div>

            <!-- Target Age -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl stat-icon-bg bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->type === 'rolling' ? 'fa-bullseye' : 'fa-users' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1"
                        style="color: var(--text-secondary);">
                        {{ $program->type === 'rolling' ? 'Target Age' : 'Age Range' }}
                    </p>
                    <h3 class="text-2xl font-black" style="color: var(--text-primary);">
                        {{ $program->type === 'rolling' ? $program->age_target . ' yrs' : $program->cohort_age_min . '-' . $program->cohort_age_max . ' yrs' }}
                    </h3>
                </div>
            </div>
        </div>

        @if($program->type === 'scheduled' && $program->start_date)
            <!-- Dates for Scheduled Programs -->
            <div
                class="glass rounded-3xl p-8 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-calendar-alt text-[#0B4D73]"></i>
                        <h4 class="text-lg font-black text-slate-800">Operational Timeline</h4>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Scheduled dates for this programme's student lifecycle.</p>
                </div>
                <div class="flex items-center gap-4 sm:gap-12">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Enrollment Starts</p>
                        <p class="text-xl font-black text-[#0B4D73]">
                            {{ \Carbon\Carbon::parse($program->start_date)->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="w-10 h-px bg-slate-200 hidden sm:block"></div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Programme Ends</p>
                        <p class="text-xl font-black text-pink-600">
                            {{ \Carbon\Carbon::parse($program->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($program->type !== 'offline')
            <!-- Contents Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">Programme Curriculum</h2>
                    </div>
                    <button onclick="document.getElementById('addContentModal').classList.remove('hidden')"
                        class="btn btn-primary btn-sm flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Content</span>
                    </button>
                </div>

                @if($program->contents && $program->contents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($program->contents as $content)
                            <div
                                class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                                <div class="p-6 flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl group-hover:bg-blue-50 group-hover:text-[#0B4D73] transition-all shadow-sm">
                                            <i
                                                class="fas {{ in_array($content->content_type, ['video', 'video_pdf']) ? 'fa-play-circle' : 'fa-file-alt' }}"></i>
                                        </div>
                                        <div class="flex gap-1">
                                            <a href="{{ route('admin.programs.contents.edit', [$program->id, $content->id]) }}"
                                                class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-all">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.programs.contents.destroy', [$program->id, $content->id]) }}"
                                                method="POST" class="inline" onsubmit="return confirm('Delete this content module?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-all">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <h4 class="font-bold text-slate-900 text-lg mb-2 leading-tight">{{ $content->title }}</h4>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                                            @if($content->content_type === 'video_pdf') Video + PDF @else
                                            {{ str_replace('_', ' ', $content->content_type) }} @endif
                                        </span>
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest">
                                            @if($program->type === 'rolling') Week {{ $content->week_number }} @else Release Cycle
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                                    <div class="flex items-center gap-3">
                                        @if(in_array($content->content_type, ['video', 'video_pdf']) && $content->youtube_url)
                                            <button onclick="openVideoModal('{{ $content->youtube_url }}')"
                                                class="text-xs font-black uppercase tracking-widest text-[#0B4D73] hover:text-blue-600 transition-colors flex items-center gap-1.5">
                                                <i class="fas fa-play text-[10px]"></i>
                                                Watch
                                            </button>
                                        @endif
                                        @if(in_array($content->content_type, ['pdf', 'pdf_only', 'video_pdf']) && $content->file_path)
                                            <a href="{{ asset('storage/' . $content->file_path) }}" target="_blank"
                                                class="text-xs font-black uppercase tracking-widest text-[#0B4D73] hover:text-blue-600 transition-colors flex items-center gap-1.5">
                                                <i class="fas fa-file-pdf text-[10px]"></i>
                                                View PDF
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                        ID: {{ str_pad($content->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass rounded-3xl p-16 text-center border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-folder-open text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Curriculum Empty</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mb-8 font-medium">This programme doesn't have any lessons or
                            resources. Start building your educational material!</p>
                        <button onclick="document.getElementById('addContentModal').classList.remove('hidden')"
                            class="btn btn-primary shadow-lg shadow-blue-900/20 px-8">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Create First Module</span>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Enrolled Children Section -->
        <div class="space-y-8 pt-10 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-tight">Enrolled Students</h2>
                        <p class="text-sm font-medium text-slate-500">Managing {{ $program->children->count() }} active
                            enrollments</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse($program->children as $child)
                    <div
                        class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl transition-all duration-300">
                        <div
                            class="p-6 border-b border-slate-100 bg-slate-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    @if($child->profile_picture)
                                        <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                                            class="w-14 h-14 rounded-2xl object-cover shadow-md border-2 border-white">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg border-2 border-white shadow-md uppercase">
                                            {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-slate-900 leading-none mb-1">{{ $child->full_name }}</h4>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        {{ $child->age }} Years • Joined
                                        {{ optional($child->pivot->created_at)->format('M d, Y') ?? 'Cycle N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.children.show', $child->id) }}"
                                    class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-100 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fas fa-user-circle"></i>
                                </a>
                                @if($program->type !== 'rolling')
                                    <form action="{{ route('admin.programs.children.unassign', [$program->id, $child->id]) }}"
                                        method="POST" onsubmit="return confirm('Expel student from this programme cohort?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-100 flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h5
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fas fa-clipboard-check text-indigo-500"></i>
                                    Mentor Observations
                                </h5>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[9px] font-bold">
                                    {{ $child->observations->where('mentor_id', $program->mentor_id)->count() }} Recorded
                                </span>
                            </div>

                            @php
                                $observations = $child->observations->filter(function ($o) use ($program) {
                                    return in_array($o->mentor_id, [$program->mentor_id]);
                                });
                            @endphp

                            <div class="space-y-3">
                                @forelse($observations->take(2) as $observation)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/50">
                                        <div class="flex items-center justify-between mb-2">
                                            <span
                                                class="text-[9px] font-black text-[#0B4D73] bg-blue-50/50 px-2 py-0.5 rounded uppercase tracking-tighter">
                                                By Lead Mentor
                                            </span>
                                            <span class="text-[9px] font-bold text-slate-400">
                                                {{ $observation->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 italic">
                                            "{{ $observation->content }}"
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-center py-6 bg-slate-50/30 rounded-2xl border border-dashed border-slate-200">
                                        <p class="text-xs text-slate-400 italic">No field observations recorded yet.</p>
                                    </div>
                                @endforelse

                                @if($observations->count() > 2)
                                    <a href="{{ route('admin.children.show', $child->id) }}#observations"
                                        class="block text-center text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-700 pt-2 transition-colors">
                                        View all {{ $observations->count() }} observations
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                        <div
                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                            <i class="fas fa-users-slash text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Programme Unpopulated</h3>
                        <p class="text-slate-500 font-medium">New student enrollments will automatically appear here once
                            assigned.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Community Link Section -->
        <div class="space-y-8 pt-10 border-t border-slate-100 mb-12">
            <div
                class="analytics-card rounded-3xl p-8 border border-slate-100/50 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-2xl transition-all duration-500 group">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-[#0B4D73] text-white flex items-center justify-center text-3xl shadow-lg shadow-blue-900/20 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-tight mb-1">Programme Community HUB</h2>
                        <p class="text-sm font-medium text-slate-500">Enter the full-screen discussion board for this
                            cohort.</p>
                    </div>
                </div>
                <a href="{{ route('admin.communities.show', $program->id) }}"
                    class="btn btn-primary px-8 py-4 rounded-2xl shadow-xl shadow-blue-900/20 flex items-center gap-3 group/btn">
                    <span>Open Community Chat</span>
                    <i
                        class="fas fa-external-link-alt text-sm group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Meta Information Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-20">
            <div class="analytics-card rounded-3xl p-8 flex items-start gap-6">
                <div
                    class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shadow-sm">
                    <i class="fas fa-server"></i>
                </div>
                <div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">Technical Artifacts</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-10">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Entry ID</span>
                            <span
                                class="text-xs font-bold text-slate-600">SYS-{{ str_pad($program->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-10">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Initialization</span>
                            <span
                                class="text-xs font-bold text-slate-600">{{ $program->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-10">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Last
                                Modified</span>
                            <span
                                class="text-xs font-bold text-slate-600">{{ $program->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="analytics-card rounded-3xl p-8 flex items-start gap-6 relative overflow-hidden">
                <div
                    class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center text-2xl shadow-sm">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="relative z-10 flex-1">
                    <h4 class="text-xl font-black text-slate-900 mb-3">Privileged Actions</h4>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.programs.edit', $program->id) }}"
                            class="flex items-center justify-between px-4 py-3 bg-[#0B4D73] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#093e5d] transition-all group">
                            <span>Adjust Parameters</span>
                            <i class="fas fa-cog group-hover:rotate-180 transition-transform"></i>
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('admin.programs.index') }}"
                                class="flex items-center justify-center py-3 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                                Program Master
                            </a>
                            <button onclick="window.print()"
                                class="flex items-center justify-center py-3 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                                Export View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Content Modal -->
    <div id="addContentModal"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full my-8 transform transition-all scale-100">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-3xl">
                <h3 class="text-2xl font-bold text-slate-900">Add Program Content</h3>
                <button onclick="document.getElementById('addContentModal').classList.add('hidden')"
                    class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.programs.contents.store', $program->id) }}" method="POST"
                enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-2">
                        <div class="flex items-center gap-2 text-red-700 font-bold">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-sm font-bold text-slate-700">Content Title *</label>
                        <input type="text" name="title" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all"
                            placeholder="e.g., Lesson 1: Introduction">
                    </div>

                    <!-- Type -->
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">Content Type *</label>
                        <select name="type" id="contentTypeSelector" onchange="toggleContentInput()" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all cursor-pointer">
                            <option value="video_pdf">Video & PDF (Lesson)</option>
                            <option value="pdf_only">PDF Only (Activity/Assignment)</option>
                            <option value="video">Video Only (Legacy)</option>
                        </select>
                    </div>



                    <!-- Dynamic Input: Link -->
                    <div id="videoInput" class="space-y-1 md:col-span-2">
                        <label class="text-sm font-bold text-slate-700">Video URL * (YouTube/Vimeo)</label>
                        <div class="relative">
                            <input type="url" name="video_url"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all"
                                placeholder="https://youtube.com/watch?v=...">
                            <i class="fab fa-youtube absolute left-4 top-1/2 -translate-y-1/2 text-red-500 text-xl"></i>
                        </div>
                    </div>

                    <!-- Dynamic Input: File -->
                    <div id="pdfInput" class="hidden space-y-1 md:col-span-2">
                        <label class="text-sm font-bold text-slate-700">Upload PDF *</label>
                        <div
                            class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-blue-400 transition-colors cursor-pointer group">
                            <input type="file" name="pdf" accept=".pdf"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-2">
                                <i
                                    class="fas fa-file-pdf text-3xl text-slate-300 group-hover:text-red-500 transition-colors"></i>
                                <p class="text-sm text-slate-500">Click to upload or drag PDF here</p>
                                <p class="text-xs text-slate-400">Max size 20MB</p>
                            </div>
                        </div>
                    </div>

                    @if($program->type === 'rolling')
                        <!-- Rolling: Target Age -->
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Target Age *</label>
                            <input type="number" name="target_age" min="1" value="5" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                        <!-- Rolling: Week & Day -->
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Release Week *</label>
                            <input type="number" name="week" min="1" value="1" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Release Day * (1-7)</label>
                            <input type="number" name="day" min="1" max="7" value="1" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Release Time *</label>
                            <input type="time" name="time" value="09:00" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @elseif($program->type === 'journey')
                        <!-- Journey: Week Offset & Day Offset -->
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Week Offset * (Week 0, 1, ...)</label>
                            <input type="number" name="week_offset" min="0" value="0" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                            <p class="text-[10px] text-slate-400">Weeks from subscription date</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Day Offset * (0-6)</label>
                            <input type="number" name="day_offset" min="0" max="6" value="0" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                            <p class="text-[10px] text-slate-400">Days within that week</p>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Availability Time *</label>
                            <input type="time" name="time_of_day" value="09:00" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @else
                        <!-- Scheduled: Release Date -->
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Publish At (Date) *</label>
                            <input type="date" name="publish_at" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @endif


                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addContentModal').classList.add('hidden')"
                        class="flex-1 px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-[2] px-6 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/20">
                        Create Content
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleContentInput() {
            const type = document.getElementById('contentTypeSelector').value;
            const videoInput = document.getElementById('videoInput');
            const pdfInput = document.getElementById('pdfInput');

            if (type === 'video_pdf' || type === 'video') {
                videoInput.classList.remove('hidden');
            } else {
                videoInput.classList.add('hidden');
            }

            if (type === 'video_pdf' || type === 'pdf_only' || type === 'pdf') {
                pdfInput.classList.remove('hidden');
            } else {
                pdfInput.classList.add('hidden');
            }
        }

        function extractYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length == 11) ? match[2] : false;
        }

        function openVideoModal(youtubeUrl) {
            const videoId = extractYouTubeId(youtubeUrl);
            if (videoId) {
                const iframe = document.getElementById('videoPlayer');
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                document.getElementById('videoModal').classList.remove('hidden');
            } else {
                window.open(youtubeUrl, '_blank');
            }
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('videoPlayer').src = '';
        }
    </script>

    <!-- Video Modal -->
    <div id="videoModal"
        class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full relative">
            <button onclick="closeVideoModal()"
                class="absolute top-4 right-4 z-10 p-2 bg-white rounded-full text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all shadow-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="aspect-video bg-slate-900 rounded-3xl overflow-hidden">
                <iframe id="videoPlayer" class="w-full h-full" style="border: none;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
@endsection