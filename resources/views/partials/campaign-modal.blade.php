{{-- Campaign Modal for Featured Programs --}}
@php
    $campaignPrograms = \App\Models\Program::where('is_featured', true)
        ->where('type', '!=', 'rolling')
        ->where('status', 'active')
        ->latest()
        ->take(3)
        ->get();
@endphp

@if($campaignPrograms->count() > 0)
    <div id="campaignModal" class="fixed inset-0 z-[200] hidden overflow-hidden">
        <!-- Backdrop with blur -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm w-full h-full text-left"
            onclick="closeCampaignModal()"></div>

        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4 w-full h-full pointer-events-none">
            <div id="campaignContent"
                class="relative w-full max-w-lg md:max-w-2xl transform scale-95 opacity-0 transition-all duration-500 max-h-[90vh] flex flex-col pointer-events-auto">

                <!-- Main Card -->
                <div
                    class="relative bg-white rounded-2xl md:rounded-[2rem] shadow-2xl overflow-hidden max-h-[85vh] flex flex-col w-full">

                    <!-- Decorative Header Background -->
                    <div
                        class="relative h-32 sm:h-48 bg-gradient-to-br from-[#0B4D73] via-cyan-600 to-teal-500 overflow-hidden flex-shrink-0">
                        <!-- Animated Patterns -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute top-4 left-10 w-24 h-24 sm:w-32 sm:h-32 border-4 border-white/30 rounded-full animate-ping"
                                style="animation-duration: 3s;"></div>
                            <div class="absolute bottom-10 right-20 w-16 h-16 sm:w-20 sm:h-20 border-4 border-white/20 rounded-full animate-ping"
                                style="animation-duration: 4s;"></div>
                        </div>

                        <!-- Floating Icons -->
                        <div class="absolute top-4 right-6 sm:top-6 sm:right-8 text-white/20 text-4xl sm:text-6xl animate-bounce"
                            style="animation-duration: 2s;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div
                            class="absolute bottom-4 left-6 sm:bottom-8 sm:left-8 text-white/15 text-2xl sm:text-4xl animate-pulse">
                            <i class="fas fa-graduation-cap"></i>
                        </div>

                        <!-- Close Button -->
                        <button onclick="closeCampaignModal()"
                            class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all hover:rotate-90 duration-300 z-10">
                            <i class="fas fa-times text-base sm:text-lg"></i>
                        </button>

                        <!-- Header Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 text-white">
                            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                <span
                                    class="px-2 py-0.5 sm:px-3 sm:py-1 bg-amber-400 text-amber-900 text-[8px] sm:text-[10px] font-black uppercase tracking-widest rounded-full animate-pulse">
                                    <i class="fas fa-fire mr-1"></i> Hot
                                </span>
                                <span class="text-white/70 text-[10px] sm:text-xs font-medium">Limited Enrollment</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-black leading-tight">
                                🌟 Featured Programmes<br>
                                <span class="text-cyan-200">Just For You!</span>
                            </h2>
                        </div>
                    </div>

                    <!-- Programs Grid (Scrollable) -->
                    <div class="p-4 sm:p-6 md:p-8 overflow-y-auto">
                        <p class="text-slate-600 mb-4 sm:mb-6 text-sm sm:text-base">
                            Discover our specially curated programmes designed to help your child thrive.
                            <span class="font-bold text-slate-800">Enroll today!</span>
                        </p>

                        <div class="space-y-3 sm:space-y-4">
                            @foreach($campaignPrograms as $index => $program)
                                <a href="{{ route('programs.explore') }}"
                                    class="group {{ $index >= 2 ? 'hidden md:flex' : 'flex' }} gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-100 hover:border-[#0B4D73]/30 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-cyan-50/50 transition-all duration-300">

                                    <!-- Thumbnail -->
                                    <div
                                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg sm:rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 group-hover:scale-105 transition-transform shadow-md">
                                        @if($program->thumbnail_path)
                                            <img src="{{ asset('storage/' . $program->thumbnail_path) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                                <i class="fas fa-book-open text-xl sm:text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <h4
                                                    class="font-bold text-slate-900 text-sm sm:text-base group-hover:text-[#0B4D73] transition-colors truncate">
                                                    {{ $program->name }}
                                                </h4>
                                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 sm:mt-1 line-clamp-2">
                                                    {{ Str::limit($program->description, 70) }}
                                                </p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <div class="text-base sm:text-lg font-black text-[#0B4D73]">
                                                    ₦{{ number_format($program->price) }}
                                                </div>
                                                @if($program->cohort_age_min)
                                                    <div class="text-[9px] sm:text-[10px] text-slate-400 font-medium">
                                                        Ages {{ $program->cohort_age_min }}-{{ $program->cohort_age_max }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Tags -->
                                        <div class="flex items-center gap-2 mt-2">
                                            <span
                                                class="px-1.5 py-0.5 bg-blue-50 text-blue-700 text-[9px] sm:text-[10px] font-bold rounded-md uppercase">
                                                {{ $program->type }}
                                            </span>
                                            @if($program->is_featured)
                                                <span
                                                    class="px-1.5 py-0.5 bg-amber-50 text-amber-700 text-[9px] sm:text-[10px] font-bold rounded-md uppercase">
                                                    <i class="fas fa-star text-[8px] mr-0.5"></i> Featured
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Arrow -->
                                    <div
                                        class="flex items-center text-slate-300 group-hover:text-[#0B4D73] transition-colors pl-1">
                                        <i
                                            class="fas fa-chevron-right text-sm sm:text-base group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- CTA Buttons -->
                        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('programs.explore') }}"
                                class="flex-1 px-5 py-3 sm:px-6 sm:py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white text-sm sm:text-base font-bold rounded-xl text-center shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-0.5 transition-all">
                                <i class="fas fa-rocket mr-2"></i>
                                Explore All
                            </a>
                            <button onclick="closeCampaignModal()"
                                class="px-5 py-3 sm:px-6 sm:py-4 bg-slate-100 text-slate-600 text-sm sm:text-base font-bold rounded-xl hover:bg-slate-200 transition-all">
                                Close
                            </button>
                        </div>
                    </div>

                    <!-- Bottom Gradient Accent -->
                    <div class="h-1.5 bg-gradient-to-r from-[#0B4D73] via-cyan-500 to-teal-400 flex-shrink-0"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CAMPAIGN_KEY = 'YPMMH_campaign_last_shown';
        const CAMPAIGN_COOLDOWN_MS = 4 * 60 * 60 * 1000; // 4 hours in milliseconds

        // Allowed pages for campaign modal
        const ALLOWED_PAGES = [
            'welcome',        // Welcome / Landing Page
            'programs.explore', // Program Catalog Page
            'program.show',    // Individual Program Page
            'index'           // Homepage
        ];

        function getCurrentPage() {
            // Get the current route name from Laravel's meta tag
            const routeMeta = document.querySelector('meta[name="current-route"]');
            if (routeMeta) {
                return routeMeta.getAttribute('content');
            }

            // Fallback: check URL pathname
            const path = window.location.pathname.toLowerCase();
            if (path === '/' || path === '/home') return 'welcome';
            if (path.includes('/programs')) return 'programs.explore';
            if (path.includes('/program/')) return 'program.show';

            return null;
        }

        function isAllowedPage() {
            const currentPage = getCurrentPage();
            if (!currentPage) return false;
            return ALLOWED_PAGES.includes(currentPage);
        }

        function shouldShowCampaign() {
            // Only show on allowed pages
            if (!isAllowedPage()) {
                return false;
            }

            // Check last shown time
            const lastShown = localStorage.getItem(CAMPAIGN_KEY);
            if (!lastShown) {
                return true; // First visit on allowed page
            }

            const timeSinceLastShown = Date.now() - parseInt(lastShown);
            return timeSinceLastShown >= CAMPAIGN_COOLDOWN_MS;
        }

        function showCampaignModal() {
            const modal = document.getElementById('campaignModal');
            const content = document.getElementById('campaignContent');

            if (modal && content) {
                modal.classList.remove('hidden');
                // Removed body overflow hidden to prevent layout shifts/width issues
                // document.body.style.overflow = 'hidden';

                // Animate in
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 50);

                // Record show time
                localStorage.setItem(CAMPAIGN_KEY, Date.now().toString());
            }
        }

        function closeCampaignModal() {
            const modal = document.getElementById('campaignModal');
            const content = document.getElementById('campaignContent');

            if (modal && content) {
                // Animate out
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);

                // Save timestamp when dismissed
                localStorage.setItem(CAMPAIGN_KEY, Date.now().toString());
            }
        }

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeCampaignModal();
            }
        });

        // Show modal on page load if conditions are met
        document.addEventListener('DOMContentLoaded', () => {
            // Small delay for better UX - let page load first
            setTimeout(() => {
                if (shouldShowCampaign()) {
                    showCampaignModal();
                }
            }, 1500); // 1.5 second delay
        });
    </script>
@endif