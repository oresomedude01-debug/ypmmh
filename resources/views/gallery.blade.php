@extends('layouts.public')

@section('title', 'YPMMH Gallery | Moments of Growth & Islamic Guidance')
@section('description', 'See our programs in action. Photos and videos of children engaging in mentorship, learning, and developing leadership skills in a faith-based environment.')
@section('keywords', 'islamic school gallery, mentorship photos, muslim youth activities, leadership workshop images')

@section('styles')
    <style>
        /* Masonry Grid Layout */
        .masonry-grid {
            columns: 2;
            column-gap: 1rem;
        }

        @media (min-width: 640px) {
            .masonry-grid {
                columns: 3;
            }
        }

        @media (min-width: 1024px) {
            .masonry-grid {
                columns: 4;
            }
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
        }

        /* Gallery item animations */
        .gallery-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .gallery-item:hover {
            transform: scale(1.02);
            z-index: 10;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-item:hover img {
            filter: brightness(1.05);
        }

        /* Lightbox styles */
        .lightbox {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .lightbox.active {
            opacity: 1;
            visibility: visible;
        }

        .lightbox-content {
            animation: lightboxIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes lightboxIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Filter pill active state */
        .filter-pill.active {
            background: linear-gradient(135deg, #0B4D73, #0891b2);
            color: white;
            box-shadow: 0 4px 15px rgba(11, 77, 115, 0.3);
        }

        /* Floating animation for featured badge */
        @keyframes float-gentle {

            0%,
            100% {
                transform: translateY(0px) rotate(-3deg);
            }

            50% {
                transform: translateY(-5px) rotate(-3deg);
            }
        }

        .float-gentle {
            animation: float-gentle 3s ease-in-out infinite;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-6">
        <!-- Compact Hero Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div class="space-y-2 text-left">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-widest">
                    <i class="fas fa-camera"></i>
                    Memories
                </div>
                <h1 class="text-2xl md:text-4xl font-black text-slate-900 leading-tight">
                    Our <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-[#0B4D73] to-cyan-600">Community</span>
                    Gallery
                </h1>
                <p class="text-sm text-slate-500 font-medium max-w-lg">
                    Capturing moments of growth, connection, and spiritual excellence.
                </p>
            </div>

            <!-- Compact Stats -->
            <div
                class="flex items-center gap-6 py-3 px-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-center">
                    <div class="text-lg font-black text-[#0B4D73]">{{ $images->total() }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Photos</div>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="text-center">
                    <div class="text-lg font-black text-[#0B4D73]">{{ $images->where('is_featured', true)->count() }}</div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Featured</div>
                </div>
            </div>
        </div>

        <!-- Filter Pills & Layout Container -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterGallery('all')"
                    class="filter-pill active px-4 py-1.5 rounded-full text-[11px] font-bold transition-all duration-300 bg-slate-100 text-slate-600 hover:bg-slate-200"
                    data-filter="all">
                    All
                </button>
                <button onclick="filterGallery('workshops')"
                    class="filter-pill px-4 py-1.5 rounded-full text-[11px] font-bold transition-all duration-300 bg-slate-100 text-slate-600 hover:bg-slate-200"
                    data-filter="workshops">
                    Workshops
                </button>
                <button onclick="filterGallery('events')"
                    class="filter-pill px-4 py-1.5 rounded-full text-[11px] font-bold transition-all duration-300 bg-slate-100 text-slate-600 hover:bg-slate-200"
                    data-filter="events">
                    Events
                </button>
                <button onclick="filterGallery('mentoring')"
                    class="filter-pill px-4 py-1.5 rounded-full text-[11px] font-bold transition-all duration-300 bg-slate-100 text-slate-600 hover:bg-slate-200"
                    data-filter="mentoring">
                    Mentoring
                </button>
            </div>
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest hidden md:block">
                {{ $images->total() }} Moments Captured
            </div>
        </div>

        <!-- Masonry Gallery Grid -->
        <div class="masonry-grid" id="galleryGrid">
            @include('partials.gallery-items', ['images' => $images])
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12 {{ $images->hasMorePages() ? '' : 'hidden' }}" id="loadMoreContainer">
            <button onclick="loadMoreImages()"
                class="inline-flex items-center gap-3 px-8 py-4 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-bold hover:border-[#0B4D73] hover:text-[#0B4D73] transition-all duration-300 shadow-sm hover:shadow-md group"
                id="loadMoreBtn">
                <span>Load More Photos</span>
                <i class="fas fa-arrow-down group-hover:translate-y-1 transition-transform"></i>
            </button>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox"
        class="fixed inset-0 z-[100] bg-slate-900/95 backdrop-blur-sm opacity-0 invisible flex items-center justify-center p-4 lightbox"
        onclick="closeLightbox(event)">
        <button onclick="closeLightbox(event)"
            class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-20">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Navigation Arrows -->
        <button onclick="navigateLightbox(-1)"
            class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-20">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button onclick="navigateLightbox(1)"
            class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all z-20">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="lightbox-content max-w-4xl w-full" onclick="event.stopPropagation()">
            <img id="lightboxImage" src="" alt="" class="w-full h-auto max-h-[70vh] object-contain rounded-2xl shadow-2xl">
            <div class="mt-6 text-center">
                <h3 id="lightboxTitle" class="text-white text-xl font-bold"></h3>
                <span id="lightboxCategory"
                    class="inline-block mt-2 px-4 py-1.5 rounded-full bg-white/10 text-white/80 text-xs font-medium uppercase tracking-wider"></span>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentPage = 1;
        let hasMore = {{ $images->hasMorePages() ? 'true' : 'false' }};
        let currentCategory = 'all';

        // Filter Gallery
        function filterGallery(category) {
            currentCategory = category;
            const items = document.querySelectorAll('.gallery-item');
            const pills = document.querySelectorAll('.filter-pill');

            // Update active pill
            pills.forEach(pill => {
                pill.classList.remove('active');
                if (pill.dataset.filter === category) {
                    pill.classList.add('active');
                }
            });

            // Filter items with animation
            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        }

        // Load More Images
        function loadMoreImages() {
            if (!hasMore) return;

            currentPage++;
            const btn = document.getElementById('loadMoreBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span>Loading...</span><i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            fetch(`{{ route('gallery') }}?page=${currentPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('galleryGrid');
                    grid.insertAdjacentHTML('beforeend', data.html);

                    hasMore = data.hasMore;
                    if (!hasMore) {
                        document.getElementById('loadMoreContainer').classList.add('hidden');
                    }

                    btn.innerHTML = originalText;
                    btn.disabled = false;

                    // Re-apply filter if not 'all'
                    if (currentCategory !== 'all') {
                        filterGallery(currentCategory);
                    }
                })
                .catch(error => {
                    console.error('Error loading images:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        // Lightbox Functions
        let currentImageIndex = 0;

        function getVisibleImages() {
            const items = document.querySelectorAll('.gallery-item');
            const visible = [];
            items.forEach(item => {
                if (item.style.display !== 'none') {
                    const img = item.querySelector('img');
                    visible.push({
                        src: img.src,
                        title: item.querySelector('.gallery-overlay span:first-child').textContent,
                        category: item.dataset.category
                    });
                }
            });
            return visible;
        }

        function openLightbox(src, title, category) {
            const lightbox = document.getElementById('lightbox');
            const image = document.getElementById('lightboxImage');
            const titleEl = document.getElementById('lightboxTitle');
            const categoryEl = document.getElementById('lightboxCategory');

            const visibleImages = getVisibleImages();
            currentImageIndex = visibleImages.findIndex(item => item.src === src);

            image.src = src;
            titleEl.textContent = title;
            categoryEl.textContent = category;

            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';

            const header = document.getElementById('main-header');
            if (header) {
                header.style.opacity = '0';
                header.style.pointerEvents = 'none';
            }
        }

        function closeLightbox(event) {
            if (event) event.stopPropagation();
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = '';

            const header = document.getElementById('main-header');
            if (header) {
                header.style.opacity = '1';
                header.style.pointerEvents = 'auto';
            }
        }

        function navigateLightbox(direction) {
            const visibleImages = getVisibleImages();
            currentImageIndex += direction;

            if (currentImageIndex < 0) currentImageIndex = visibleImages.length - 1;
            if (currentImageIndex >= visibleImages.length) currentImageIndex = 0;

            const item = visibleImages[currentImageIndex];
            document.getElementById('lightboxImage').src = item.src;
            document.getElementById('lightboxTitle').textContent = item.title;
            document.getElementById('lightboxCategory').textContent = item.category;
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
        });
    </script>
@endsection