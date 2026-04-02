<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    @include('partials.seo')
    @include('partials.pwa')
    <meta name="current-route" content="{{ Route::currentRouteName() }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0B4D73',
                        secondary: '#075985',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: #0B4D73;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        .bg-grid {
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(11, 77, 115, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(11, 77, 115, 0.05) 1px, transparent 1px);
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(11, 77, 115, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 20px 40px -5px rgba(11, 77, 115, 0.1);
            border-color: rgba(11, 77, 115, 0.2);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }
    </style>
    @yield('styles')
</head>

<body
    class="bg-slate-50 text-slate-800 antialiased relative overflow-x-hidden selection:bg-[#0B4D73] selection:text-white">

    <!-- Background Decoration -->
    <div class="fixed inset-0 bg-grid z-0 pointer-events-none"></div>
    <div
        class="fixed top-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-blue-100/30 blur-[120px] pointer-events-none">
    </div>
    <div
        class="fixed bottom-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-cyan-100/30 blur-[100px] pointer-events-none">
    </div>

    <!-- Navigation -->
    <header id="main-header" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
        <nav
            class="glass max-w-7xl mx-auto mt-4 mx-2 sm:mx-4 md:mx-auto rounded-xl sm:rounded-2xl px-3 sm:px-4 md:px-6 py-2.5 sm:py-3 md:py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group flex-shrink-0">
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-bold text-sm sm:text-lg text-slate-900 leading-tight tracking-tight capitalize">{{ app_name() }}</span>
                        <span
                            class="text-[8px] sm:text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">{{ app_tagline() }}</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <ul
                    class="hidden md:flex items-center gap-8 font-bold text-xs uppercase tracking-widest text-slate-500 flex-shrink-0">
                    <li><a href="{{ route('about') }}"
                            class="{{ request()->routeIs('about') ? 'text-primary font-black' : 'hover:text-primary' }} transition-colors">About</a>
                    </li>
                    <li><a href="{{ route('programs.explore') }}"
                            class="{{ request()->routeIs('programs.explore') ? 'text-primary font-black' : 'hover:text-primary' }} transition-colors">Programmes</a>
                    </li>
                    <li><a href="{{ route('gallery') }}"
                            class="{{ request()->routeIs('gallery') ? 'text-primary font-black' : 'hover:text-primary' }} transition-colors">Gallery</a>
                    </li>
                    <li><a href="{{ route('blog') }}"
                            class="{{ request()->routeIs('blog') ? 'text-primary font-black' : 'hover:text-primary' }} transition-colors">Blog</a>
                    </li>
                </ul>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    {{-- PWA Install Button (Desktop) --}}
                    @include('partials.pwa-install-button')

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl bg-[#0B4D73] text-white text-[10px] sm:text-xs font-bold shadow-lg shadow-blue-900/10 hover:bg-slate-900 transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-flex text-xs font-bold text-slate-600 hover:text-primary transition-colors px-2 sm:px-4 py-2">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 sm:px-6 sm:py-2.5 rounded-xl bg-[#0B4D73] text-white text-[10px] sm:text-xs font-bold shadow-lg shadow-blue-900/10 hover:bg-slate-900 transform hover:-translate-y-0.5 transition-all whitespace-nowrap">
                            Join Now
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()"
                        class="md:hidden p-1.5 sm:p-2 rounded-lg hover:bg-slate-100 transition-colors ml-1">
                        <i class="fas fa-bars text-lg sm:text-xl text-slate-700" id="mobileMenuIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pt-4 border-t border-slate-100">
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}"
                            class="block py-2 px-3 rounded-lg font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">About</a>
                    </li>
                    <li><a href="{{ route('programs.explore') }}"
                            class="block py-2 px-3 rounded-lg font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">Programmes</a>
                    </li>
                    <li><a href="{{ route('gallery') }}"
                            class="block py-2 px-3 rounded-lg font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">Gallery</a>
                    </li>
                    <li><a href="{{ route('blog') }}"
                            class="block py-2 px-3 rounded-lg font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all">Blog</a>
                    </li>
                    @guest
                        <li class="pt-2 border-t border-slate-100">
                            <a href="{{ route('login') }}"
                                class="block py-2 px-3 rounded-lg font-semibold text-slate-600 hover:bg-slate-50 transition-all">Login</a>
                        </li>
                    @endguest
                    {{-- PWA Install Button (Mobile Menu) --}}
                    <li class="pwa-mobile-menu-item" style="display:none;">
                        <a id="pwa-mobile-install-btn" onclick="PWAInstallButton.handleClick(); return false;" href="#"
                            class="flex items-center gap-3 py-2 px-3 rounded-lg font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all">
                            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            <span>📱 Download App</span>
                            <span
                                class="ml-auto text-[10px] font-bold uppercase tracking-wider text-emerald-500 bg-emerald-100 px-2 py-0.5 rounded-full">Free</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="relative z-10 pt-28 min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 mt-20 bg-white/50 backdrop-blur-md border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-9 h-9 flex items-center justify-center">
                        <img src="{{ app_logo() }}" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-xl text-slate-900 capitalize">{{ app_name() }}</span>
                </div>
                <p class="text-slate-500 max-w-sm leading-relaxed mb-6 text-sm">
                    Nurturing the next generation of productive, purpose-driven young Muslims. Join our community of
                    growth today.
                </p>
                <div class="flex gap-4">
                    <a href="{{ \App\Models\Setting::get('social_facebook', '#') }}"
                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0B4D73] hover:text-white transition-all"><i
                            class="fab fa-facebook-f text-sm"></i></a>
                    <a href="{{ \App\Models\Setting::get('social_instagram', '#') }}"
                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0B4D73] hover:text-white transition-all"><i
                            class="fab fa-instagram text-sm"></i></a>
                    <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}"
                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0B4D73] hover:text-white transition-all"><i
                            class="fab fa-twitter text-sm"></i></a>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-sm text-slate-900 mb-6">Quick Links</h4>
                <ul class="space-y-3 text-sm font-medium text-slate-500">
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Our Story</a></li>
                    <li><a href="{{ route('programs.explore') }}" class="hover:text-primary transition-colors">Explore
                            Programs</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-primary transition-colors">Gallery</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-primary transition-colors">Blog & Updates</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-sm text-slate-900 mb-6">Support</h4>
                <ul class="space-y-3 text-sm font-medium text-slate-500">
                    <li><a href="{{ route('about') }}#contact" class="hover:text-primary transition-colors">Contact
                            Us</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-100 py-8 text-center text-xs text-slate-400 font-medium">
            © {{ date('Y') }} Young Productive Muslim Mentoring Hub. All rights reserved.
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            menu.classList.toggle('hidden');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }

        // Smart Header Logic
        let lastScroll = 0;
        const header = document.getElementById('main-header');

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.style.transform = 'translateY(0)';
                header.style.opacity = '1';
                return;
            }

            if (currentScroll > lastScroll && currentScroll > 100) {
                // Scrolling down - hide
                header.style.transform = 'translateY(-100%)';
                header.style.opacity = '0';
            } else {
                // Scrolling up - show
                header.style.transform = 'translateY(0)';
                header.style.opacity = '1';
            }
            lastScroll = currentScroll;
        });
    </script>
    @yield('scripts')

    {{-- Campaign Modal for Featured Programs --}}
    @include('partials.campaign-modal')

    {{-- PWA Install Prompt --}}
    @include('partials.pwa-install-prompt')
</body>

</html>