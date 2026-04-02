@extends('layouts.public')

@section('title', 'Explore Mentorship Programs | Child Counselling & Leadership')
@section('description', 'Browse our range of Islamic mentorship programs for children. From leadership development to Quranic values, find the perfect program for your child\'s growth.')
@section('keywords', 'mentorship programs, islamic courses for kids, leadership training, quranic values, child development, online islamic classes')

@section('styles')
    <style>
        /* Masonry Grid for better responsive layout */
        .programs-masonry {
            columns: 2;
            column-gap: 1rem;
        }

        @media (min-width: 640px) {
            .programs-masonry {
                columns: 2;
                column-gap: 1.25rem;
            }
        }

        @media (min-width: 1024px) {
            .programs-masonry {
                columns: 3;
                column-gap: 1.5rem;
            }
        }

        @media (min-width: 1280px) {
            .programs-masonry {
                columns: 4;
                column-gap: 1.5rem;
            }
        }

        .program-item {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }

        .program-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .program-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .program-card img {
            transition: filter 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .program-card:hover img {
            filter: brightness(1.1);
        }

        /* Type badge animation */
        .type-badge {
            animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Price highlight animation */
        .price-highlight {
            background: linear-gradient(135deg, #0B4D73, #0891b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .modal-backdrop {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(8px);
        }

        /* Icon animations */
        .age-icon {
            transition: transform 0.3s ease;
        }

        .program-card:hover .age-icon {
            transform: scale(1.2);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-6 md:py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div class="space-y-2">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-widest">
                    <i class="fas fa-book"></i>
                    Learning Paths
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Available Trainings</h1>
                <p class="text-slate-500 text-sm font-medium">Find your next growth path in our curated hub.</p>
            </div>

            <form action="{{ route('programs.explore') }}" method="GET"
                class="flex flex-col sm:flex-row flex-wrap gap-3 w-full md:w-auto">
                <div class="relative group flex-1 sm:flex-none">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-primary"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search programmes..."
                        class="pl-10 pr-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none transition-all w-full md:w-64 shadow-sm">
                </div>

                <select name="type" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none transition-all shadow-sm">
                    <option value="">All Types</option>
                    <option value="scheduled" {{ request('type') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="journey" {{ request('type') == 'journey' ? 'selected' : '' }}>Journey</option>
                    <option value="offline" {{ request('type') == 'offline' ? 'selected' : '' }}>Physical Hub</option>
                </select>
            </form>
        </div>

        <!-- Programs Grid -->
        @if($programs->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-100 italic">
                <i class="fas fa-compass text-5xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 text-sm font-medium">No results matching your filters.</p>
                <a href="{{ route('programs.explore') }}"
                    class="text-primary text-xs font-bold mt-3 inline-block hover:underline">Clear all filters</a>
            </div>
        @else
            <div class="programs-masonry mb-12">
                @foreach($programs as $program)
                    <div class="program-item">
                        <div onclick="openProgramModal({{ json_encode($program) }})"
                            class="program-card bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col cursor-pointer group hover:shadow-xl h-full">
                            <div class="relative aspect-video overflow-hidden bg-slate-100">
                                @if($program->thumbnail_path)
                                    <img src="{{ asset('storage/' . $program->thumbnail_path) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-slate-200 text-4xl">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3 type-badge">
                                    <span
                                        class="px-3 py-1.5 bg-white/95 backdrop-blur rounded-lg text-[9px] font-bold uppercase text-[#0B4D73] shadow-md inline-block">
                                        {{ $program->type }}
                                    </span>
                                </div>

                                @if($program->price < 5000)
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="px-2 py-1 bg-green-500/90 backdrop-blur rounded-lg text-[8px] font-bold uppercase text-white shadow-md">
                                            <i class="fas fa-star text-yellow-300 mr-1"></i>Popular
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <h3
                                    class="text-sm font-bold text-slate-900 mb-2 group-hover:text-primary transition-colors line-clamp-2 leading-snug">
                                    {{ $program->name }}
                                </h3>
                                <p class="text-slate-500 text-[11px] leading-relaxed line-clamp-2 mb-4 min-h-8">
                                    {{ $program->description }}
                                </p>

                                <div class="mt-auto pt-4 space-y-3 border-t border-slate-50">
                                    <div class="flex items-center justify-between">
                                        <span class="price-highlight text-sm">
                                            ₦ {{ number_format($program->price, 0) }}
                                        </span>
                                        <div class="flex items-center gap-2 text-slate-400 text-[10px] font-bold">
                                            <i class="fas fa-users text-[9px] age-icon"></i>
                                            @if($program->cohort_age_min)
                                                <span
                                                    class="text-slate-600">{{ $program->cohort_age_min }}-{{ $program->cohort_age_max }}</span>
                                            @else
                                                <span class="text-slate-600">7+</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $programs->links() }}
            </div>
        @endif
    </div>

    <!-- Program Detail Modal -->
    <div id="programModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="modal-backdrop fixed inset-0" onclick="closeProgramModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="relative bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden animate-fade-in-up">
                <button onclick="closeProgramModal()"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-all z-10">
                    <i class="fas fa-times text-xs"></i>
                </button>

                <div id="modalVideoContainer" class="aspect-video bg-slate-900 hidden relative">
                    <div id="youtubePlayer" class="w-full h-full"></div>
                </div>
                <div id="modalImageContainer" class="aspect-video bg-slate-100">
                    <img id="modalImg" src="" class="w-full h-full object-cover">
                </div>

                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span id="modalType"
                            class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[9px] font-bold uppercase tracking-widest"></span>
                        <span id="modalAge" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"></span>
                    </div>

                    <h2 id="modalTitle" class="text-2xl font-bold text-slate-900 mb-4 tracking-tight"></h2>

                    <div class="prose prose-sm max-w-none text-slate-500 mb-8 leading-relaxed">
                        <p id="modalDesc"></p>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Cost
                                Assessment</span>
                            <span id="modalPrice" class="text-xl font-bold text-slate-900"></span>
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <form id="subscribeForm" action="{{ route('subscription.initiate') }}" method="POST"
                                class="flex-1 sm:flex-none">
                                @csrf
                                <input type="hidden" name="program_id" id="modalProgramId" value="">
                                <button type="submit"
                                    class="w-full px-8 py-3 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white rounded-xl font-bold uppercase tracking-widest text-xs text-center shadow-lg shadow-blue-900/20 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-bolt"></i>
                                    Subscribe Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let player;

        // Load YouTube API
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        function onYouTubeIframeAPIReady() {
            // We'll create the player instance when opening the modal
        }

        function openProgramModal(program) {
            const modal = document.getElementById('programModal');
            const title = document.getElementById('modalTitle');
            const desc = document.getElementById('modalDesc');
            const type = document.getElementById('modalType');
            const age = document.getElementById('modalAge');
            const price = document.getElementById('modalPrice');
            const img = document.getElementById('modalImg');
            const videoContainer = document.getElementById('modalVideoContainer');
            const imageContainer = document.getElementById('modalImageContainer');
            const programIdField = document.getElementById('modalProgramId');

            // Set the program ID for subscription form
            programIdField.value = program.id;

            title.innerText = program.name;
            desc.innerText = program.description;
            type.innerText = program.type;
            age.innerText = program.cohort_age_min ? `Ages ${program.cohort_age_min} - ${program.cohort_age_max}` : 'All Ages (7+)';
            price.innerText = `₦ ${new Intl.NumberFormat().format(program.price)}`;

            const thumbUrl = program.thumbnail_path ? `/storage/${program.thumbnail_path}` : '';
            img.src = thumbUrl;

            // Video Handling
            if (program.youtube_url) {
                videoContainer.classList.remove('hidden');
                imageContainer.classList.add('hidden');

                const videoId = extractYoutubeId(program.youtube_url);
                if (videoId) {
                    if (player) {
                        player.loadVideoById(videoId);
                    } else {
                        player = new YT.Player('youtubePlayer', {
                            height: '100%',
                            width: '100%',
                            videoId: videoId,
                            playerVars: { 'autoplay': 0, 'rel': 0 },
                        });
                    }
                }
            } else {
                videoContainer.classList.add('hidden');
                imageContainer.classList.remove('hidden');
                if (player) player.stopVideo();
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProgramModal() {
            const modal = document.getElementById('programModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (player) player.stopVideo();
        }

        function extractYoutubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }

        // Close on escape
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeProgramModal();
        });
    </script>
@endsection