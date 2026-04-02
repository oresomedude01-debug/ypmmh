@extends('layouts.dashboard')

@section('title', 'Edit Program Content')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.programs.show', $program->id) }}"
                class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-6">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Program</span>
            </a>
            <h1
                class="text-4xl font-bold bg-gradient-to-r from-[#0B4D73] via-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Edit Program Content
            </h1>
            <p class="text-lg text-slate-600 mt-3">{{ $program->name }}</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 mb-8 animate-in fade-in slide-in-from-top">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                <p class="text-emerald-700 font-semibold">{{ session('success') }}</p>
                <button onclick="this.parentElement.style.display='none'"
                    class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white rounded-3xl shadow-lg border border-slate-100">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 rounded-t-3xl">
                <h2 class="text-2xl font-bold text-slate-900">{{ $content->title }}</h2>
            </div>

            <form action="{{ route('admin.programs.contents.update', [$program->id, $content->id]) }}" method="POST"
                enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

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
                        <input type="text" name="title" value="{{ old('title', $content->title) }}" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all"
                            placeholder="e.g., Lesson 1: Introduction">
                    </div>

                    <!-- Type -->
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">Content Type *</label>
                        <select name="type" id="contentTypeSelector" onchange="toggleContentInput()" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all cursor-pointer">
                            <option value="video_pdf" {{ in_array($content->content_type, ['video', 'video_pdf']) ? 'selected' : '' }}>Video &
                                PDF (Lesson)</option>
                            <option value="pdf_only" {{ in_array($content->content_type, ['pdf', 'pdf_only']) ? 'selected' : '' }}>PDF Only
                                (Activity/Assignment)</option>
                        </select>
                    </div>

                    <!-- Dynamic Input: Link -->
                    <div id="videoInput"
                        class="space-y-1 md:col-span-2 {{ !in_array($content->content_type, ['video', 'video_pdf']) ? 'hidden' : '' }}">
                        <label class="text-sm font-bold text-slate-700">Video URL * (YouTube/Vimeo)</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="url" name="video_url" value="{{ old('video_url', $content->youtube_url) }}"
                                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all"
                                    placeholder="https://youtube.com/watch?v=...">
                                <i class="fab fa-youtube absolute left-4 top-1/2 -translate-y-1/2 text-red-500 text-xl"></i>
                            </div>
                            <button type="button" onclick="previewVideo()"
                                class="px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Input: File -->
                    <div id="pdfInput"
                        class="space-y-1 md:col-span-2 {{ !in_array($content->content_type, ['pdf', 'pdf_only', 'video_pdf']) ? 'hidden' : '' }}">
                        <label class="text-sm font-bold text-slate-700">Upload PDF</label>
                        @if($content->file_path)
                            <div
                                class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-red-500"></i>
                                    <span class="text-sm text-blue-700 font-medium">Current file is set</span>
                                </div>
                                <a href="{{ Storage::url($content->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    View File
                                </a>
                            </div>
                        @endif
                        <div
                            class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-blue-400 transition-colors cursor-pointer group">
                            <input type="file" name="pdf" accept=".pdf"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-2">
                                <i
                                    class="fas fa-file-pdf text-3xl text-slate-300 group-hover:text-red-500 transition-colors"></i>
                                <p class="text-sm text-slate-500">Click to upload or drag PDF here</p>
                                <p class="text-xs text-slate-400">Leave blank to keep current file. Max size 20MB</p>
                            </div>
                        </div>
                    </div>

                    @if($program->type === 'rolling')
                        <!-- Rolling: Week & Day -->
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Release Week *</label>
                            <input type="number" name="week" min="1" value="{{ old('week', $content->week_number) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Release Day * (1-7)</label>
                            <input type="number" name="day" min="1" max="7" value="{{ old('day', $content->day_number) }}"
                                required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Release Time *</label>
                            <input type="time" name="time" value="{{ old('time', $content->time_of_day) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @elseif($program->type === 'journey')
                        <!-- Journey: Week Offset & Day Offset -->
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Week Offset * (Week 0, 1, ...)</label>
                            <input type="number" name="week_offset" min="0" value="{{ old('week_offset', $content->week_offset) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                            <p class="text-[10px] text-slate-400">Weeks from subscription date</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-slate-700">Day Offset * (0-6)</label>
                            <input type="number" name="day_offset" min="0" max="6" value="{{ old('day_offset', $content->day_offset) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                            <p class="text-[10px] text-slate-400">Days within that week</p>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Availability Time *</label>
                            <input type="time" name="time_of_day" value="{{ old('time_of_day', $content->time_of_day) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @else
                        <!-- Scheduled/Offline: Release Date -->
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700">Publish At (Date) *</label>
                            <input type="date" name="publish_at"
                                value="{{ old('publish_at', $content->publish_at ? $content->publish_at->format('Y-m-d') : '') }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73]">
                        </div>
                    @endif

                    <!-- Is Active -->
                    <div class="space-y-1">
                        <label class="text-sm font-bold text-slate-700">Status</label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" {{ $content->is_active ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 text-[#0B4D73] focus:ring-[#0B4D73]">
                            <span class="text-slate-700 font-medium">Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.programs.show', $program->id) }}"
                        class="flex-1 px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-[2] px-6 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/20">
                        Update Content
                    </button>
                </div>
            </form>

            <!-- Delete Button -->
            <div class="p-8 border-t border-slate-100 bg-red-50/50 rounded-b-3xl">
                <form action="{{ route('admin.programs.contents.destroy', [$program->id, $content->id]) }}" method="POST"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this content?')"
                        class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all inline-flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Delete Content</span>
                    </button>
                </form>
            </div>
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

        function extractYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length == 11) ? match[2] : false;
        }

        function previewVideo() {
            const url = document.querySelector('input[name="video_url"]').value;
            const videoId = extractYouTubeId(url);
            if (videoId) {
                const iframe = document.getElementById('videoPreview');
                iframe.src = `https://www.youtube.com/embed/${videoId}`;
                document.getElementById('videoPreviewModal').classList.remove('hidden');
            } else {
                alert('Please enter a valid YouTube URL');
            }
        }

        function closeVideoPreview() {
            document.getElementById('videoPreviewModal').classList.add('hidden');
            document.getElementById('videoPreview').src = '';
        }
    </script>

    <!-- Video Preview Modal -->
    <div id="videoPreviewModal"
        class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full relative">
            <button onclick="closeVideoPreview()"
                class="absolute top-4 right-4 z-10 p-2 bg-white rounded-full text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all shadow-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="aspect-video bg-slate-900 rounded-3xl overflow-hidden">
                <iframe id="videoPreview" class="w-full h-full" style="border: none;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

@endsection