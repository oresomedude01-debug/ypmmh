@extends('layouts.dashboard')

@section('title', 'Program Catalog')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Explore Programs</h1>
            <p class="text-slate-500">Discover scheduled courses and educational journeys for your children.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-semibold border border-blue-100 italic">
                <i class="fas fa-sparkles mr-2"></i>New courses every month
            </span>
        </div>
    </div>

    @if($featuredPrograms->isNotEmpty())
    <!-- Featured Programs Carousel -->
    <div class="relative group">
        <div id="carousel" class="relative overflow-hidden rounded-[2.5rem] shadow-2xl shadow-blue-900/10">
            <div class="flex transition-transform duration-700 ease-out" id="carousel-track">
                @foreach($featuredPrograms as $featured)
                    <div class="min-w-full relative h-[450px] md:h-[500px]">
                        <!-- Background Image -->
                        @if($featured->thumbnail_path)
                            <img src="{{ asset('storage/' . $featured->thumbnail_path) }}" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-[#0B4D73] via-[#1a6a9b] to-indigo-900"></div>
                        @endif
                        <!-- Overlays -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 to-transparent md:block hidden"></div>
                        
                        <!-- Content -->
                        <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-16 space-y-4 md:space-y-6">
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-1.5 bg-amber-400 text-slate-900 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-amber-400/20">
                                    <i class="fas fa-star mr-1"></i> Featured Odyssey
                                </span>
                                <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md text-white rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/20">
                                    {{ ucfirst($featured->type) }}
                                </span>
                            </div>
                            
                            <h2 class="text-4xl md:text-6xl font-black text-white leading-tight max-w-2xl drop-shadow-2xl">
                                {{ $featured->name }}
                            </h2>
                            
                            <p class="text-slate-200 text-sm md:text-lg max-w-xl line-clamp-2 md:line-clamp-3 leading-relaxed drop-shadow-md">
                                {{ $featured->description }}
                            </p>
                            
                            <div class="flex flex-wrap items-center gap-4 md:gap-6 pt-2">
                                @if(auth()->user()->hasRole('Child'))
                                    <form action="{{ route('subscription.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="program_id" value="{{ $featured->id }}">
                                        <button type="submit" 
                                                class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-sm md:text-base hover:bg-blue-50 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                                            Start Your Journey <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </form>
                                @else
                                    <button onclick="openEnrollModal({{ $featured->id }}, '{{ addslashes($featured->name) }}', {{ json_encode($featured->eligible_child_ids ?? []) }})" 
                                            class="px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-sm md:text-base hover:bg-blue-50 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                                        Enroll Now <i class="fas fa-arrow-right"></i>
                                    </button>
                                @endif

                                @if($featured->youtube_url)
                                    <button onclick="openTrailer('{{ $featured->youtube_url }}')" 
                                            class="px-8 py-4 bg-white/10 backdrop-blur-md text-white border border-white/30 rounded-2xl font-black text-sm md:text-base hover:bg-white/20 transition-all active:scale-95 flex items-center gap-2">
                                        <i class="fab fa-youtube text-red-500"></i> Watch Trailer
                                    </button>
                                @endif

                                <div class="hidden md:flex items-center gap-4 text-white/80 pl-4 border-l border-white/20">
                                    <div class="text-center px-4">
                                        <p class="text-[10px] uppercase font-bold tracking-widest text-white/60">Ages</p>
                                        <p class="font-black">
                                            @if($featured->type === 'rolling')
                                                {{ $featured->age_target }}
                                            @else
                                                {{ $featured->cohort_age_min }}-{{ $featured->cohort_age_max }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-center px-4">
                                        <p class="text-[10px] uppercase font-bold tracking-widest text-white/60">Value</p>
                                        <p class="font-black text-emerald-400">
                                            {{ $featured->is_free ? 'FREE' : '₦' . number_format($featured->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Controls -->
            @if($featuredPrograms->count() > 1)
                <button onclick="moveCarousel(-1)" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-white hover:text-slate-900">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="moveCarousel(1)" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-white hover:text-slate-900">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Indicators -->
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-3">
                    @foreach($featuredPrograms as $index => $featured)
                        <button onclick="goToSlide({{ $index }})" 
                                class="w-10 h-1 rounded-full transition-all duration-300 carousel-indicator {{ $loop->first ? 'bg-white w-16' : 'bg-white/30' }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($availablePrograms->isEmpty())
        <div class="glass rounded-3xl p-12 text-center space-y-4">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-400">
                <i class="fas fa-search fa-2x"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">No available programs yet</h3>
            <p class="text-slate-500 max-w-md mx-auto">Check back soon! We are constantly preparing new transformative journeys for our students.</p>
        </div>
    @else
        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($availablePrograms as $program)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col group hover:translate-y-[-4px] transition-all duration-300">
                    <!-- Media Section -->
                    <div class="relative aspect-video bg-slate-900">
                        @if($program->thumbnail_path)
                            <img src="{{ asset('storage/' . $program->thumbnail_path) }}" 
                                 alt="{{ $program->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-100">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#0B4D73] to-[#1a6a9b] flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-white/20 text-5xl"></i>
                            </div>
                        @endif

                        <!-- Badge Overlay -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[10px] font-bold uppercase tracking-wider text-[#0B4D73]">
                                {{ $program->cohort_age_min }}-{{ $program->cohort_age_max }} YRS
                            </span>
                            @if($program->is_free)
                                <span class="px-3 py-1 bg-emerald-500 text-white rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    FREE
                                </span>
                            @else
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    ₦{{ number_format($program->price, 2) }}
                                </span>
                            @endif
                        </div>

                        <!-- Video Play Button Overlay (if trailer exists) -->
                        @if($program->youtube_url)
                            <button onclick="openTrailer('{{ $program->youtube_url }}')" 
                                    class="absolute inset-0 flex items-center justify-center group/play">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30 group-hover/play:scale-110 group-hover/play:bg-red-600 group-hover/play:border-red-500 transition-all">
                                    <i class="fas fa-play ml-1 text-xl"></i>
                                </div>
                            </button>
                        @endif
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $program->name }}</h3>
                            <p class="text-slate-500 text-sm line-clamp-3 leading-relaxed">
                                {{ $program->description }}
                            </p>
                        </div>

                        <div class="mt-auto space-y-4">
                            <!-- Stats/Info -->
                            <div class="grid grid-cols-2 gap-3 py-4 border-y border-slate-50">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="far fa-calendar-alt text-blue-500"></i>
                                    <span class="text-[11px] font-semibold">
                                        @if($program->start_date)
                                            Starts {{ \Carbon\Carbon::parse($program->start_date)->format('M d') }}
                                        @elseif($program->type === 'journey')
                                            Self-paced
                                        @elseif($program->type === 'offline')
                                            TBC/Ongoing
                                        @else
                                            Ongoing
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="far fa-clock text-purple-500"></i>
                                    <span class="text-[11px] font-semibold">Limited Seats</span>
                                </div>
                            </div>

                            <!-- CTA Section -->
                            <div class="flex items-center gap-2">
                                @if(auth()->user()->hasRole('Child'))
                                    <div class="flex-1 flex gap-2">
                                        <form action="{{ route('subscription.initiate') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="program_id" value="{{ $program->id }}">
                                            <button type="submit" 
                                                    class="w-full bg-[#0B4D73] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#093e5d] transition-all shadow-lg active:scale-95">
                                                Enroll Me
                                            </button>
                                        </form>
                                        <form action="{{ route('child.request') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="program_id" value="{{ $program->id }}">
                                            <button type="submit" 
                                                    class="w-full bg-white border border-[#0B4D73] text-[#0B4D73] py-3 rounded-2xl font-bold text-sm hover:bg-blue-50 transition-all active:scale-95">
                                                Ask Parent
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <button onclick="openEnrollModal({{ $program->id }}, '{{ addslashes($program->name) }}', {{ json_encode($program->eligible_child_ids ?? []) }})" 
                                            class="flex-1 bg-[#0B4D73] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/20 active:scale-95">
                                        Enroll Mentee
                                    </button>
                                @endif

                                @if($program->youtube_url)
                                    <button onclick="openTrailer('{{ $program->youtube_url }}')" 
                                            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 transition-all border border-red-100"
                                            title="Watch Jingle">
                                        <i class="fab fa-youtube text-lg"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if(auth()->user()->hasRole('Parent'))
<!-- Enrollment Modal (Only for Parents) -->
<div id="enrollModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[2100] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl animate-in zoom-in duration-300">
        <div class="p-8 text-center bg-slate-50 border-b border-slate-100">
            <div class="w-16 h-16 bg-blue-100 text-[#0B4D73] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900" id="modalProgramName">Enroll Mentee</h2>
            <p class="text-slate-500 text-sm mt-1">Select which mentee you'd like to enroll</p>
        </div>

        <form action="{{ route('subscription.initiate') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="program_id" id="modalProgramId">
            <input type="hidden" name="user_role" value="Parent">
            
            <div class="space-y-3">
                @foreach($children as $child)
                    <label class="relative block cursor-pointer group">
                        <input type="radio" name="child_id" value="{{ $child->id }}" class="peer sr-only" required>
                        <div class="p-4 rounded-2xl border-2 border-slate-100 peer-checked:border-[#0B4D73] peer-checked:bg-blue-50/50 transition-all flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden">
                                    @if($child->profile_image)
                                        <img src="{{ asset('storage/' . $child->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold">
                                            {{ substr($child->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-slate-900">{{ $child->first_name }} {{ $child->last_name }}</p>
                                    <p class="text-xs text-slate-500">Age: {{ $child->age }}</p>
                                </div>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-slate-200 peer-checked:border-[#0B4D73] flex items-center justify-center">
                                <div class="w-3 h-3 rounded-full bg-[#0B4D73] scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeEnrollModal()" 
                        class="flex-1 px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-6 py-3 rounded-2xl bg-[#0B4D73] text-white font-bold hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/20">
                    Confirm Enrollment
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Trailer Modal -->
<div id="trailerModal" class="fixed inset-0 bg-slate-900/95 z-[2100] hidden flex items-center justify-center p-4">
    <div class="relative w-full max-w-5xl aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl">
        <button onclick="closeTrailer()" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center z-10 transition-all backdrop-blur-md">
            <i class="fas fa-times"></i>
        </button>
        <iframe id="trailerIframe" class="w-full h-full" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>

<script>
// Carousel Logic
let currentSlide = 0;
const totalSlides = {{ $featuredPrograms->count() }};
const track = document.getElementById('carousel-track');
const indicators = document.querySelectorAll('.carousel-indicator');

function updateCarousel() {
    if (!track) return;
    track.style.transform = `translateX(-${currentSlide * 100}%)`;
    
    // Update indicators
    indicators.forEach((ind, i) => {
        if (i === currentSlide) {
            ind.classList.add('bg-white', 'w-16');
            ind.classList.remove('bg-white/30');
        } else {
            ind.classList.remove('bg-white', 'w-16');
            ind.classList.add('bg-white/30');
        }
    });
}

function moveCarousel(direction) {
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    updateCarousel();
}

function goToSlide(index) {
    currentSlide = index;
    updateCarousel();
}

// Auto-advance
@if($featuredPrograms->count() > 1)
setInterval(() => {
    moveCarousel(1);
}, 6000);
@endif

function openEnrollModal(programId, programName, eligibleChildIds = []) {
    document.getElementById('modalProgramId').value = programId;
    document.getElementById('modalProgramName').textContent = 'Enroll in ' + programName;
    
    // Filter children labels in modal
    const childLabels = document.querySelectorAll('#enrollModal label');
    let firstVisible = null;

    childLabels.forEach(label => {
        const input = label.querySelector('input[name="child_id"]');
        const childId = parseInt(input.value);
        
        if (eligibleChildIds.length > 0 && !eligibleChildIds.includes(childId)) {
            label.classList.add('hidden');
            input.disabled = true;
            input.checked = false;
        } else {
            label.classList.remove('hidden');
            input.disabled = false;
            if (!firstVisible) firstVisible = input;
        }
    });

    // Auto-select the first eligible child
    if (firstVisible) {
        firstVisible.checked = true;
    }

    document.getElementById('enrollModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEnrollModal() {
    document.getElementById('enrollModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openTrailer(url) {
    // Convert YouTube URL to embed URL if needed
    let embedUrl = url;
    if (url.includes('watch?v=')) {
        embedUrl = url.replace('watch?v=', 'embed/');
    } else if (url.includes('youtu.be/')) {
        embedUrl = url.replace('youtu.be/', 'youtube.com/embed/');
    }
    
    // Add autoplay
    if (!embedUrl.includes('?')) {
        embedUrl += '?autoplay=1';
    } else {
        embedUrl += '&autoplay=1';
    }

    document.getElementById('trailerIframe').src = embedUrl;
    document.getElementById('trailerModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTrailer() {
    document.getElementById('trailerIframe').src = '';
    document.getElementById('trailerModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals on clicking outside
window.onclick = function(event) {
    const enrollModal = document.getElementById('enrollModal');
    const trailerModal = document.getElementById('trailerModal');
    if (event.target == enrollModal) {
        closeEnrollModal();
    }
    if (event.target == trailerModal) {
        closeTrailer();
    }
}
</script>
@endsection
