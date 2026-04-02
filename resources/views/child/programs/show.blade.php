@extends('layouts.dashboard')

@section('title', $program->name)

@section('styles')
    <style>
        .lesson-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-tap-highlight-color: transparent;
        }
        .lesson-card:active {
            transform: scale(0.97);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }
        .checkmark-done {
            background: #10b981;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in pb-24 md:pb-10">
        
        <!-- App-Style Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <a href="{{ route('child.dashboard') }}" class="inline-flex items-center gap-2 text-blue-200 hover:text-white transition-colors mb-6 text-xs font-black uppercase tracking-widest">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Journey</span>
                </a>
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-2">
                        <span class="px-2 py-0.5 bg-white/10 rounded-md text-[8px] font-black uppercase tracking-widest border border-white/10">Active Quest</span>
                        <h1 class="text-2xl md:text-4xl font-black tracking-tight leading-tight">{{ $program->name }}</h1>
                        <p class="text-blue-100/70 text-sm font-medium line-clamp-2">{{ $program->description }}</p>
                    </div>

                    @if($program->mentor)
                        <div class="flex items-center gap-3 bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B4D73] flex items-center justify-center font-black overflow-hidden shadow-inner">
                                @if($program->mentor->profile_picture)
                                    <img src="{{ asset('storage/' . $program->mentor->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($program->mentor->first_name ?? 'M', 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-[8px] font-black uppercase text-blue-200/50">Your Guide</p>
                                <p class="text-xs font-bold">{{ $program->mentor->full_name }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($program->type !== 'offline')
            <!-- Lessons List -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-lg font-black text-slate-900">Mission Stages</h2>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        {{ count($completedLessonIds) }} / {{ $program->contents->count() }} Stage Clear
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($program->contents as $index => $content)
                        @php $isCompleted = in_array($content->id, $completedLessonIds); @endphp
                        <div class="lesson-card bg-white rounded-[2rem] p-5 shadow-sm border {{ $isCompleted ? 'border-emerald-100 bg-emerald-50/20' : 'border-slate-100' }} relative group active:bg-slate-50 overflow-hidden">
                            
                            @if($isCompleted)
                                <div class="absolute -right-6 -top-6 w-16 h-16 checkmark-done rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white translate-x-[-8px] translate-y-[8px] text-lg"></i>
                                </div>
                            @endif

                            <div class="flex items-start gap-4">
                                <!-- Stage Indicator -->
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-2xl {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-50 text-[#0B4D73]' }} flex flex-col items-center justify-center shadow-sm">
                                        <span class="text-[8px] font-black uppercase leading-none mb-0.5">Stage</span>
                                        <span class="text-xl font-black leading-none">{{ $index + 1 }}</span>
                                    </div>
                                </div>

                                <!-- Lesson Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">
                                            {{ in_array($content->content_type, ['video', 'video_pdf']) ? 'Visual Intel' : 'Document' }}
                                        </span>
                                        @if($program->type === 'rolling')
                                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded text-[8px] font-black uppercase">Week {{ $content->week_number }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-base font-black text-slate-900 mb-4">{{ $content->title }}</h3>

                                    <!-- Quick Actions -->
                                    <div class="flex flex-wrap gap-2">
                                        @if(in_array($content->content_type, ['video', 'video_pdf']) && $content->youtube_url)
                                            <button onclick="openVideoModal('{{ $content->youtube_url }}')" 
                                                class="flex items-center gap-2 px-3 py-1.5 bg-[#0B4D73] text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-md shadow-blue-900/10 active:scale-95 transition-all">
                                                <i class="fas fa-play text-[8px]"></i> Play Intel
                                            </button>
                                        @endif

                                        @if(in_array($content->content_type, ['pdf', 'pdf_only', 'video_pdf']) && $content->file_path)
                                            <a href="{{ asset('storage/' . $content->file_path) }}" target="_blank" 
                                               class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all">
                                                <i class="fas fa-file-pdf text-[8px]"></i> Read Mission
                                            </a>
                                        @endif

                                        @if(!$isCompleted)
                                            <form action="{{ route('child.lessons.complete', $content->id) }}" method="POST" class="w-full mt-2">
                                                @csrf
                                                <button type="submit" 
                                                    class="w-full py-3 bg-yellow-400 text-[#0B4D73] rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-yellow-400/20 active:scale-95 transition-all">
                                                    Complete Stage & Claim +20 XP
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-full mt-2 py-3 bg-emerald-100/50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center border border-emerald-100">
                                                Stage Secured ✨
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-slate-50 p-12 rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Intelligence Found Yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Community Call -->
        <div class="bg-[#0B4D73] rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden active:scale-[0.98] transition-transform">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl shrink-0 shadow-inner">
                    <i class="fas fa-comments text-blue-200"></i>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-xl font-black mb-1">Village Chat</h3>
                    <p class="text-blue-100/60 text-xs font-medium">Chat with your fellow explorers and mentor about this quest.</p>
                </div>
                <div class="md:ml-auto w-full md:w-auto">
                    <a href="{{ route('child.communities.show', $program->id) }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-white text-[#0B4D73] rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-black/20">
                        Enter Room <i class="fas fa-external-link-alt text-[8px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>


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
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
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
                document.body.style.overflow = 'hidden';
            }
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('videoPlayer').src = '';
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection