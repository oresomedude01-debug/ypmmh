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
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 animate-in fade-in slide-in-from-top">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                <p class="text-emerald-700 font-semibold">{{ session('success') }}</p>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex-1 space-y-3">
                <a href="{{ route('mentor.programs.index') }}"
                    class="inline-flex items-center gap-2 text-slate-500 hover:text-[#0B4D73] transition-colors mb-2 font-bold text-xs uppercase tracking-widest">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to My Programmes</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-black bg-gradient-to-r from-[#0B4D73] via-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $program->name }}
                </h1>
                <p class="text-md text-slate-500 leading-relaxed max-w-3xl font-medium">{{ $program->description }}</p>
            </div>
        </div>

        <!-- KPIs Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Type -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->type === 'rolling' ? 'fa-sync-alt' : 'fa-calendar-check' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Programme Type</p>
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
                        <div class="w-12 h-12 rounded-xl stat-icon-bg {{ $statusColors[$program->status] ?? 'bg-slate-50 text-slate-500' }} flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->status === 'active' ? 'fa-check-circle' : 'fa-info-circle' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">System Status</p>
                    <h3 class="text-2xl font-black capitalize {{ Str::contains($statusColors[$program->status] ?? '', 'text-emerald') ? 'text-emerald-500' : (Str::contains($statusColors[$program->status] ?? '', 'text-amber') ? 'text-amber-500' : 'text-slate-500') }}">
                        {{ $program->status === 'active' ? 'Active' : $program->status }}
                    </h3>
                </div>
            </div>

            <!-- Total Contents -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-folder-open"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Curriculum Modules</p>
                    <h3 class="text-2xl font-black" style="color: var(--text-primary);">{{ $program->contents->count() }}</h3>
                </div>
            </div>

            <!-- Age Range -->
            <div class="analytics-card rounded-2xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl stat-icon-bg bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $program->type === 'rolling' ? 'fa-bullseye' : 'fa-users' }}"></i>
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-sm font-bold uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Target Age</p>
                    <h3 class="text-2xl font-black" style="color: var(--text-primary);">
                        {{ $program->type === 'rolling' ? $program->age_target . ' yrs' : $program->cohort_age_min . '-' . $program->cohort_age_max . ' yrs' }}
                    </h3>
                </div>
            </div>
        </div>

        @if($program->type === 'scheduled' && $program->start_date)
            <!-- Dates for Scheduled Programs -->
            <div class="glass rounded-3xl p-8 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
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
                        <h2 class="text-2xl font-black text-slate-900">Programme curriculum</h2>
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
                            <div class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                                <div class="p-6 flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl group-hover:bg-blue-50 group-hover:text-[#0B4D73] transition-all shadow-sm">
                                            <i class="fas {{ in_array($content->content_type, ['video', 'video_pdf']) ? 'fa-play-circle' : 'fa-file-alt' }}"></i>
                                        </div>
                                        <div class="flex gap-1">
                                            <a href="{{ route('mentor.programs.contents.edit', [$program->id, $content->id]) }}"
                                                class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-all">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <button onclick="openReportModal('{{ $content->id }}')"
                                                class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-all">
                                                <i class="fas fa-flag text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <h4 class="font-bold text-slate-900 text-lg mb-2 leading-tight">{{ $content->title }}</h4>
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                                            @if($content->content_type === 'video_pdf') Video + PDF @else {{ str_replace('_', ' ', $content->content_type) }} @endif
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest">
                                            @if($program->type === 'rolling') Week {{ $content->week_number }} @else Release Cycle @endif
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
                        <p class="text-slate-500 max-w-sm mx-auto mb-8 font-medium">This programme doesn't have any lessons or resources. Start building your educational material!</p>
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
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-tight">Enrolled Students</h2>
                        <p class="text-sm font-medium text-slate-500">Managing {{ $program->children->count() }} active enrollments</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($program->children as $child)
                    <div class="glass rounded-3xl p-6 border border-slate-100/50 hover:shadow-xl transition-all duration-300 flex items-center gap-4 group">
                        <div class="relative">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" alt="{{ $child->full_name }}"
                                    class="w-16 h-16 rounded-2xl object-cover shadow-md border-2 border-white">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xl border-2 border-white shadow-md uppercase">
                                    {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white shadow-sm"></div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-black text-slate-900 leading-tight truncate">{{ $child->full_name }}</h4>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">{{ $child->age }} Years Old</p>
                            <a href="{{ auth()->user()->hasRole('Admin') ? route('admin.children.show', $child->id) : route('mentor.children.show', $child->id) }}"
                                class="text-xs font-black text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition-colors">
                                <span>Core Profile</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                            <i class="fas fa-user-friends text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">Programme Unpopulated</h3>
                        <p class="text-slate-500 font-medium">Student enrollments assigned to you will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Community Link Section -->
        <div class="space-y-8 pt-10 border-t border-slate-100 mb-20">
            <div class="analytics-card rounded-3xl p-8 border border-slate-100/50 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-2xl transition-all duration-500 group">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-[#0B4D73] text-white flex items-center justify-center text-3xl shadow-lg shadow-blue-900/20 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 leading-tight mb-1">Programme Community HUB</h2>
                        <p class="text-sm font-medium text-slate-500">Enter the full-screen discussion board for this cohort.</p>
                    </div>
                </div>
                <a href="{{ route('mentor.communities.show', $program->id) }}" 
                   class="btn btn-primary px-8 py-4 rounded-2xl shadow-xl shadow-blue-900/20 flex items-center gap-3 group/btn">
                    <span>Open Community Chat</span>
                    <i class="fas fa-external-link-alt text-sm group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform"></i>
                </a>
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

            <form action="{{ route('mentor.programs.contents.store', $program->id) }}" method="POST"
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
                        <label class="text-sm font-bold text-slate-700">Upload PDF</label>
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

            if (type === 'video_pdf') {
                videoInput.classList.remove('hidden');
                pdfInput.classList.remove('hidden');
            } else { // type === 'pdf_only'
                videoInput.classList.add('hidden');
                pdfInput.classList.remove('hidden');
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function () {
            toggleContentInput();
        });

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

    <!-- Report Modal -->
    <div id="reportModal"
        class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full transform transition-all scale-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-amber-50 rounded-t-3xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fas fa-flag"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Report Content</h3>
                </div>
                <button onclick="document.getElementById('reportModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('reports.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="reportable_id" id="reportableId">
                <input type="hidden" name="reportable_type" value="App\Models\ProgramContent">

                <div class="space-y-4">
                    <p class="text-slate-600 text-sm">Please provide a reason for reporting this content. Admins will be
                        notified immediately.</p>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Reason for reporting *</label>
                        <textarea name="reason" rows="4" required minlength="5"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"
                            placeholder="Describe the issue..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('reportModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-all shadow-lg shadow-amber-900/20">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReportModal(contentId) {
            document.getElementById('reportableId').value = contentId;
            document.getElementById('reportModal').classList.remove('hidden');
        }
    </script>

@endsection