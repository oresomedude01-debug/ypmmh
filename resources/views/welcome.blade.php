<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    @include('partials.seo')
    @include('partials.pwa')
    <meta name="current-route" content="welcome">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Inter for a tech/clean look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('icons/icon.png') }}">
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
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden !important;
            width: 100%;
            position: relative;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Grid Background Pattern */
        .bg-grid {
            background-size: 60px 60px;
            background-image:
                linear-gradient(to right, rgba(11, 77, 115, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(11, 77, 115, 0.03) 1px, transparent 1px);
        }

        /* Animated gradient background */
        .gradient-animate {
            background: linear-gradient(-45deg, #f0f9ff, #e0f2fe, #f0fdfa, #ecfeff);
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Glassmorphism */
        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(11, 77, 115, 0.08);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 50px -12px rgba(11, 77, 115, 0.15);
            border-color: rgba(11, 77, 115, 0.15);
        }

        /* Program Card Styles */
        .program-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .program-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(11, 77, 115, 0.2);
        }

        .program-card:hover .program-overlay {
            opacity: 1;
        }

        .program-card:hover .program-thumb {
            transform: scale(1.1);
        }

        /* Carousel Styles */
        .carousel-container {
            scroll-behavior: smooth;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        /* Floating Elements */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .float-slow {
            animation: float-slow 8s ease-in-out infinite;
        }

        /* Pulse glow effect */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(11, 77, 115, 0.3);
            }

            50% {
                box-shadow: 0 0 40px rgba(11, 77, 115, 0.5);
            }
        }

        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* Badge shimmer */
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        /* Islamic Pattern */
        .islamic-pattern {
            background-color: #0B4D73;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255, 255, 255, .05) 35px, rgba(255, 255, 255, .05) 70px),
                repeating-linear-gradient(-45deg, transparent, transparent 35px, rgba(255, 255, 255, .03) 35px, rgba(255, 255, 255, .03) 70px);
        }

        /* Fade in from bottom */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Star rating */
        .star-rating {
            color: #fbbf24;
        }

        /* Blob shapes */
        .blob-1 {
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(11, 77, 115, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            top: -200px;
            right: -200px;
            animation: float-slow 20s ease-in-out infinite;
        }

        .blob-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            bottom: -100px;
            left: -200px;
            animation: float 25s ease-in-out infinite reverse;
        }

        /* Nav link underline effect */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
            border-radius: 1px;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Mobile menu animation */
        .mobile-menu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-menu.hidden {
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
        }

        /* Featured badge */
        .featured-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* Modal styles */
        .modal-backdrop {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(10px);
        }

        /* Mobile menu overflow fix */
        #mobileMenu {
            max-width: 100%;
            overflow: hidden;
        }

        #mobileMenu ul {
            max-width: 100%;
            overflow: hidden;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Navbar scroll fade effect */
        #main-header {
            opacity: 1;
            transition: opacity 0.4s ease-in-out;
        }

        #main-header.navbar-hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>

<body
    class="gradient-animate text-slate-800 antialiased relative overflow-x-hidden selection:bg-[#0B4D73]/20 selection:text-[#0B4D73]">

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- ================= HEADER / NAV ================= -->
    <!-- ================= HEADER / NAV ================= -->
    <header id="main-header"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 w-full overflow-x-hidden">
        <nav
            class="glass mx-auto mt-4 rounded-2xl px-3 sm:px-4 md:px-6 py-2.5 sm:py-3 md:py-4 w-[calc(100%-1rem)] sm:w-[calc(100%-2rem)] md:w-full max-w-7xl">
            <div class="flex items-center justify-between gap-2 w-full overflow-hidden">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group min-w-0">
                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                        <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span
                            class="font-bold text-sm sm:text-base md:text-lg text-slate-900 leading-tight tracking-tight capitalize truncate block max-w-[140px] sm:max-w-none">{{ app_name() }}</span>
                        <span
                            class="text-[7px] sm:text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-tight block max-w-full break-words">{{ app_tagline() }}</span>
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
                        class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors ml-1 focus:outline-none">
                        <i class="fas fa-bars text-xl text-slate-700" id="mobileMenuIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pt-4 border-t border-slate-100">
                <ul class="space-y-1">
                    <li><a href="{{ route('about') }}"
                            class="block py-2.5 px-4 rounded-xl font-semibold text-slate-600 hover:bg-blue-50 hover:text-[#0B4D73] transition-all">About</a>
                    </li>
                    <li><a href="{{ route('programs.explore') }}"
                            class="block py-2.5 px-4 rounded-xl font-semibold text-slate-600 hover:bg-blue-50 hover:text-[#0B4D73] transition-all">Programmes</a>
                    </li>
                    <li><a href="{{ route('gallery') }}"
                            class="block py-2.5 px-4 rounded-xl font-semibold text-slate-600 hover:bg-blue-50 hover:text-[#0B4D73] transition-all">Gallery</a>
                    </li>
                    <li><a href="{{ route('blog') }}"
                            class="block py-2.5 px-4 rounded-xl font-semibold text-slate-600 hover:bg-blue-50 hover:text-[#0B4D73] transition-all">Blog</a>
                    </li>
                    @guest
                        <li class="pt-2 mt-2 border-t border-slate-100">
                            <a href="{{ route('login') }}"
                                class="block py-2.5 px-4 rounded-xl font-semibold text-slate-600 hover:bg-blue-50 hover:text-[#0B4D73] transition-all">Login</a>
                        </li>
                    @endguest
                    {{-- PWA Install Button (Mobile Menu) --}}
                    <li class="pwa-mobile-menu-item">
                        <a id="pwa-mobile-install-btn" onclick="PWAInstallButton.handleClick(); return false;" href="#"
                            class="flex items-center gap-3 py-2.5 px-4 rounded-xl font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-all">
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

    <!-- Spacer for fixed header -->
    <div class="h-24 md:h-28"></div>

    <!-- ================= HERO ================= -->
    <section
        class="relative z-10 max-w-7xl mx-auto px-6 py-8 md:py-12 grid md:grid-cols-2 gap-8 items-center overflow-hidden">
        <div>
            <!-- Pill Badge -->
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-xs font-bold text-[#0B4D73] mb-6">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Now Enrolling for 2026
            </div>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-[1.1] text-slate-900">
                Building Confident, <br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-[#0B4D73] via-cyan-600 to-teal-500">Purpose-Driven</span>
                <br>
                Young Muslims
            </h1>

            <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-lg">
                A faith-inspired mentoring hub helping young people grow with
                <span class="font-bold text-slate-800">confidence</span>,
                <span class="font-bold text-slate-800">character</span>, and
                <span class="font-bold text-slate-800">direction</span>.
            </p>

            <div class="mt-6 flex gap-3 flex-wrap">
                <a href="{{ route('enroll') }}"
                    class="px-7 py-4 rounded-xl text-white font-bold shadow-xl shadow-blue-900/15 hover:shadow-blue-900/25 transition-all transform hover:-translate-y-1 flex items-center gap-3 bg-gradient-to-r from-[#0B4D73] to-cyan-700">
                    Enroll Your Child
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>

                <a href="#programs"
                    class="px-7 py-4 rounded-xl bg-white/80 border border-slate-200 text-slate-700 font-bold hover:bg-white hover:border-slate-300 transition-all flex items-center gap-3 backdrop-blur-sm">
                    <i class="fa-solid fa-play-circle text-[#0B4D73]"></i>
                    View Programmes
                </a>

                {{-- PWA Download Button (Hero) --}}
                <button id="pwa-hero-install-btn" onclick="PWAInstallButton.handleClick()"
                    class="px-7 py-4 rounded-xl font-bold shadow-xl shadow-emerald-500/15 hover:shadow-emerald-500/25 transition-all transform hover:-translate-y-1 flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download App
                </button>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-10 flex items-center gap-6 text-sm text-slate-500">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-green-500"></i>
                    <span>Safe Environment</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-graduate text-blue-500"></i>
                    <span>Expert Mentors</span>
                </div>
            </div>
        </div>

        <div class="relative group">
            <!-- Decorative gradient frame -->
            <div
                class="absolute -inset-6 rounded-3xl bg-gradient-to-r from-blue-200/50 via-cyan-200/50 to-teal-200/50 opacity-60 blur-2xl group-hover:opacity-100 transition-opacity duration-700">
            </div>

            <div
                class="glass relative rounded-3xl p-4 shadow-2xl shadow-blue-900/10 rotate-1 hover:rotate-0 transition-all duration-700">
                <img src="{{ asset('artifacts/hero_muslim_students.png') }}" alt="Happy young Muslim students"
                    class="rounded-2xl w-full object-cover h-64 sm:h-[400px]"
                    onerror="this.src='https://images.unsplash.com/photo-1628036838383-7c980337c73a?auto=format&fit=crop&w=1000&q=80'">

                <!-- Floating stats card -->
                <div
                    class="absolute -bottom-4 left-4 sm:-bottom-6 sm:-left-6 bg-white p-3 sm:p-5 rounded-2xl shadow-xl flex items-center gap-2 sm:gap-4 float-animation max-w-[calc(100%-2rem)]">
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center text-green-600">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <div>
                        <div class="font-extrabold text-xl sm:text-2xl text-slate-900">{{ $latestPrograms->count() }}+
                        </div>
                        <div class="text-slate-500 text-[10px] sm:text-xs font-medium">Active Programmes</div>
                    </div>
                </div>

                <!-- Floating badge -->
                <div
                    class="absolute -top-4 -right-4 bg-white px-4 py-2 rounded-xl shadow-lg flex items-center gap-2 float-slow">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                    <span class="font-bold text-sm text-slate-800">Top Rated</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= DIRECTOR'S MESSAGE ================= -->
    <section class="max-w-7xl mx-auto px-4 md:px-6 py-12 md:py-16 relative overflow-hidden">
        <!-- Background Decorations -->
        <div
            class="absolute top-10 right-0 w-64 h-64 bg-gradient-to-br from-blue-100/20 to-cyan-100/20 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-10 left-0 w-64 h-64 bg-gradient-to-tr from-teal-100/20 to-emerald-100/20 rounded-full blur-3xl">
        </div>

        <div class="relative flex flex-col lg:flex-row lg:items-center gap-8 lg:gap-12">
            <!-- Left Side: Director's Photo with Creative Effects -->
            <div class="w-full max-w-sm mx-auto lg:w-80 flex-shrink-0">
                <div class="relative group">
                    <!-- Animated Glow Background -->
                    <div
                        class="absolute -inset-4 bg-gradient-to-br from-[#0B4D73]/30 via-cyan-400/20 to-teal-500/30 rounded-3xl opacity-0 group-hover:opacity-100 blur-2xl transition-all duration-500 animate-pulse">
                    </div>

                    <!-- Photo Container -->
                    <div class="relative bg-white rounded-3xl p-2 shadow-2xl border border-slate-100">
                        <div
                            class="relative aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                            <!-- Replace with actual director photo -->
                            <div
                                class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-blue-50 to-cyan-50">
                                <i class="fas fa-user-tie text-7xl text-slate-400/50"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Badge -->
                    <div
                        class="absolute -bottom-4 -right-4 bg-gradient-to-br from-amber-400 to-orange-500 w-16 h-16 rounded-2xl flex items-center justify-center shadow-xl border-4 border-white transform group-hover:scale-110 transition-transform">
                        <i class="fas fa-quote-right text-white text-2xl"></i>
                    </div>
                </div>

                <!-- Director Info -->
                <div class="text-center mt-8 space-y-1">
                    <h3 class="text-lg font-black text-slate-900">Fatimoh Samuel</h3>
                    <p class="text-slate-500 text-sm">(Ummu Amin)</p>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-bold uppercase tracking-widest mx-auto mt-2">
                        <i class="fas fa-crown"></i>
                        Founding Director
                    </div>
                </div>
            </div>

            <!-- Right Side: Message Content -->
            <div class="flex-1 space-y-5">
                <!-- Section Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200">
                    <i class="fas fa-message text-[#0B4D73]"></i>
                    <span class="text-[#0B4D73] text-xs font-bold uppercase tracking-widest">A Message from Our
                        Director</span>
                </div>

                <!-- Main Quote -->
                <div class="relative space-y-4">
                    <blockquote class="text-xl md:text-2xl font-bold text-slate-900 leading-snug">
                        <span class="text-5xl text-blue-200/50 mr-2">"</span>
                        Every child is a treasure, a trust from Allah.
                    </blockquote>
                    <p class="text-slate-600 text-base leading-relaxed md:text-lg">
                        Our mission is to help unlock their potential while keeping their hearts connected to their
                        Creator.
                    </p>
                </div>

                <!-- Message Body -->
                <div class="space-y-4 text-slate-600 text-sm md:text-base leading-relaxed">
                    <p>
                        <span class="font-semibold text-slate-900">Assalamu Alaikum wa Rahmatullahi wa
                            Barakatuhu,</span>
                    </p>
                    <p>
                        Welcome to YPMMH. As a parent myself, I understand the challenges of raising confident,
                        God-conscious children in today's fast-paced world. That's why we created this safe space where
                        young Muslims can learn, grow, and thrive.
                    </p>
                    <p>
                        Our programmes blend Islamic values with practical life skills, preparing children not just for
                        academic success, but for a purposeful life that benefits themselves, their families, and the
                        Ummah.
                    </p>
                    <p class="font-semibold text-slate-900 text-base">
                        Together, let's raise a generation that makes us proud—in this life and the next, <i
                            class="text-emerald-600">InshaAllah.</i>
                    </p>
                </div>

                <!-- Signature -->
                <div class="pt-6 border-t-2 border-blue-100 flex items-center gap-4">
                    <div class="text-3xl font-['Brush_Script_MT',cursive] text-[#0B4D73]">Ummu Amin</div>
                    <div class="flex gap-2">
                        <a href="{{ \App\Models\Setting::get('social_linkedin', '#') }}"
                            class="w-10 h-10 rounded-full bg-blue-50 hover:bg-blue-500 hover:text-white flex items-center justify-center text-[#0B4D73] transition-all text-base shadow-sm">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}"
                            class="w-10 h-10 rounded-full bg-blue-50 hover:bg-blue-500 hover:text-white flex items-center justify-center text-[#0B4D73] transition-all text-base shadow-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= ISLAMIC CORE VALUES ================= -->
    <section class="max-w-7xl mx-auto px-4 md:px-6 py-12 md:py-16 relative">
        <div class="flex flex-col lg:flex-row lg:items-start gap-8 lg:gap-12">
            <!-- Values Grid (Left) -->
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                    <!-- Value 1 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-emerald-50 to-green-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-emerald-100/50 h-full hover:border-emerald-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-100 to-green-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-hands-praying text-lg text-emerald-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Taqwa
                                (God-Consciousness)</h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Building awareness of Allah in every action.
                            </p>
                        </div>
                    </div>

                    <!-- Value 2 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-blue-100/50 h-full hover:border-blue-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-quran text-lg text-blue-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Ilm (Knowledge)
                            </h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Seeking beneficial knowledge for growth.
                            </p>
                        </div>
                    </div>

                    <!-- Value 3 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-purple-50 to-violet-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-purple-100/50 h-full hover:border-purple-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-violet-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-heart text-lg text-purple-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Akhlaq
                                (Character)</h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Cultivating noble character traits.
                            </p>
                        </div>
                    </div>

                    <!-- Value 4 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-amber-50 to-yellow-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-amber-100/50 h-full hover:border-amber-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-yellow-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-hands-helping text-lg text-amber-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Khidmah
                                (Service)</h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Service to others and community.
                            </p>
                        </div>
                    </div>

                    <!-- Value 5 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-red-50 to-pink-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-red-100/50 h-full hover:border-red-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-pink-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-seedling text-lg text-red-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Tazkiyah
                                (Self-Purification)</h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Self-awareness and personal growth.
                            </p>
                        </div>
                    </div>

                    <!-- Value 6 -->
                    <div class="group">
                        <div
                            class="bg-gradient-to-br from-teal-50 to-cyan-50 p-5 md:p-6 rounded-2xl hover:shadow-lg transition-all duration-300 border border-teal-100/50 h-full hover:border-teal-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-100 to-cyan-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-lg text-teal-600"></i>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-slate-900 mb-2 leading-snug">Ukhuwah
                                (Brotherhood)</h4>
                            <p class="text-slate-600 text-xs md:text-sm leading-relaxed">
                                Community and lifelong bonds.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Heading Section (Right) - Sticky on Desktop -->
            <div class="lg:w-80 lg:sticky lg:top-20 flex-shrink-0">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-widest">
                        <i class="fas fa-star-and-crescent"></i>
                        Islamic Foundation
                    </div>
                    <div class="space-y-3">
                        <h2
                            class="text-3xl md:text-4xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                            Rooted in <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">Faith</span>,
                            Built for <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">Success</span>
                        </h2>
                        <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                            Every program is designed with Islamic values at its core, empowering young Muslims to excel
                            spiritually and academically.
                        </p>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="hidden lg:flex items-center gap-3 pt-6">
                        <div class="flex-1 h-px bg-gradient-to-r from-emerald-200 to-transparent"></div>
                        <i class="fas fa-mosque text-2xl text-emerald-600/30"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FEATURED PROGRAMMES SHOWCASE ================= -->
    @if($featuredPrograms->count() > 0)
        <section id="programs" class="max-w-7xl mx-auto px-4 md:px-6 py-12 md:py-16 relative">
            <!-- Background Elements -->
            <div
                class="absolute -top-40 right-0 w-96 h-96 bg-gradient-to-bl from-amber-200/10 to-transparent rounded-full blur-3xl">
            </div>
            <div
                class="absolute -bottom-20 left-1/2 w-96 h-96 bg-gradient-to-tr from-orange-200/10 to-transparent rounded-full blur-3xl">
            </div>

            <!-- Section Header -->
            <div class="relative mb-12">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-3">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 text-amber-700">
                            <i class="fas fa-fire-flame-curved text-lg animate-bounce"></i>
                            <span class="text-xs font-bold uppercase tracking-widest">Trending Now</span>
                        </div>
                        <div class="space-y-2">
                            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                                Hot <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 via-orange-600 to-red-600">Programmes</span>
                            </h2>
                            <p class="text-slate-600 text-base md:text-lg max-w-lg leading-relaxed">Discover our most
                                sought-after mentoring programmes, carefully designed to transform young minds and futures.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('programs.explore') }}"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-blue-900/20 transition-all group">
                        Explore All
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Featured Carousel -->
            <div class="relative group">
                <!-- Carousel Navigation -->
                <button onclick="scrollCarousel('featured', -1)"
                    class="absolute -left-6 top-1/2 -translate-y-1/2 z-20 w-14 h-14 rounded-full bg-white shadow-lg border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0B4D73] hover:text-white hover:shadow-xl transition-all hidden lg:flex group-hover:block">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <button onclick="scrollCarousel('featured', 1)"
                    class="absolute -right-6 top-1/2 -translate-y-1/2 z-20 w-14 h-14 rounded-full bg-white shadow-lg border border-slate-100 flex items-center justify-center text-slate-600 hover:bg-[#0B4D73] hover:text-white hover:shadow-xl transition-all hidden lg:flex group-hover:block">
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>

                <div id="featuredCarousel" class="carousel-container flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory">
                    @foreach($featuredPrograms as $program)
                        <div onclick="openProgramModal({{ json_encode($program) }})"
                            class="program-card flex-shrink-0 w-full sm:w-96 snap-center bg-white rounded-3xl overflow-hidden cursor-pointer group border border-slate-100 hover:border-amber-200 transition-all shadow-sm hover:shadow-2xl hover:shadow-amber-900/10">

                            <!-- Thumbnail -->
                            <div class="relative h-56 overflow-hidden bg-gradient-to-br from-slate-50 to-slate-100">
                                @if($program->thumbnail_path)
                                    <img src="{{ asset('storage/' . $program->thumbnail_path) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="fas fa-graduation-cap text-6xl text-slate-400/40"></i>
                                    </div>
                                @endif

                                <!-- Gradient Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-6">
                                    <div class="space-y-2 w-full">
                                        <span class="text-white font-bold text-sm flex items-center gap-2">
                                            <i class="fas fa-play-circle"></i> Tap to Discover
                                        </span>
                                        <div
                                            class="w-12 h-1 bg-white/40 rounded-full group-hover:bg-white group-hover:w-20 transition-all duration-300">
                                        </div>
                                    </div>
                                </div>

                                <!-- Featured Badge with Glow -->
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="featured-badge px-4 py-2 rounded-full text-[10px] font-bold uppercase text-white shadow-lg flex items-center gap-2 bg-gradient-to-r from-amber-600 to-orange-600">
                                        <i class="fas fa-fire text-sm animate-bounce"></i>
                                        Featured
                                    </span>
                                </div>

                                <!-- Type Badge -->
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1.5 bg-white/95 backdrop-blur rounded-full text-[10px] font-bold uppercase text-[#0B4D73] shadow-md border border-white/50">
                                        {{ $program->type }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 space-y-4">
                                <div>
                                    <h3
                                        class="text-lg font-black text-slate-900 mb-2 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-amber-600 group-hover:to-orange-600 transition-all line-clamp-2">
                                        {{ $program->name }}
                                    </h3>
                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-2">
                                        {{ $program->description }}
                                    </p>
                                </div>

                                <div class="space-y-4">
                                    <!-- Rating -->
                                    <div class="flex items-center gap-1">
                                        <div class="flex gap-0.5">
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <i class="fas fa-star-half-alt text-amber-400 text-xs"></i>
                                        </div>
                                        <span class="text-[10px] text-slate-500 font-semibold">(48 reviews)</span>
                                    </div>

                                    <!-- Footer -->
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <div class="space-y-0.5">
                                            <div
                                                class="text-xl font-black bg-gradient-to-r from-amber-600 to-orange-600 text-transparent bg-clip-text">
                                                ₦{{ number_format($program->price, 0) }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">investment</div>
                                        </div>
                                        <div
                                            class="flex items-center gap-2 px-3 py-2 rounded-full bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                                            <i class="fas fa-users text-[10px]"></i>
                                            @if($program->cohort_age_min)
                                                {{ $program->cohort_age_min }}-{{ $program->cohort_age_max }}
                                            @else
                                                7+
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Carousel Dots (Optional) -->
            <div class="flex justify-center gap-2 mt-8 md:hidden">
                @foreach($featuredPrograms as $index => $program)
                    <div class="w-2 h-2 rounded-full bg-slate-300 hover:bg-[#0B4D73] transition-all cursor-pointer"></div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ================= IMPACT STATISTICS ================= -->
    <section class="relative py-20 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle, #0B4D73 1px, transparent 1px); background-size: 30px 30px;">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6 relative">
            <!-- Creative Header -->
            <div class="mb-12">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 mb-4">
                    <i class="fas fa-sparkles text-lg"></i>
                    <span class="text-xs font-bold uppercase tracking-widest">Our Impact & Achievement</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight">
                    Making a Real
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600">Difference</span>
                </h2>
            </div>

            <!-- Stats Grid - Modern Layout with Animations -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Stat Card 1 -->
                <div class="group">
                    <div
                        class="relative bg-gradient-to-br from-blue-50 to-cyan-50 p-6 md:p-8 rounded-2xl border border-blue-100 hover:border-blue-300 transition-all duration-300 hover:shadow-lg">
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-400/0 to-cyan-400/0 group-hover:from-blue-400/10 group-hover:to-cyan-400/10 transition-all">
                        </div>
                        <div class="relative space-y-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="text-3xl md:text-4xl font-black text-slate-900">500+</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Students
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="group">
                    <div
                        class="relative bg-gradient-to-br from-green-50 to-emerald-50 p-6 md:p-8 rounded-2xl border border-green-100 hover:border-green-300 transition-all duration-300 hover:shadow-lg">
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-green-400/0 to-emerald-400/0 group-hover:from-green-400/10 group-hover:to-emerald-400/10 transition-all">
                        </div>
                        <div class="relative space-y-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-graduation-cap text-white text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="text-3xl md:text-4xl font-black text-slate-900">50+</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Expert Mentors
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="group">
                    <div
                        class="relative bg-gradient-to-br from-purple-50 to-violet-50 p-6 md:p-8 rounded-2xl border border-purple-100 hover:border-purple-300 transition-all duration-300 hover:shadow-lg">
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-purple-400/0 to-violet-400/0 group-hover:from-purple-400/10 group-hover:to-violet-400/10 transition-all">
                        </div>
                        <div class="relative space-y-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-book-open text-white text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="text-3xl md:text-4xl font-black text-slate-900">
                                    {{ $latestPrograms->count() }}+
                                </div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Programs</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="group">
                    <div
                        class="relative bg-gradient-to-br from-amber-50 to-orange-50 p-6 md:p-8 rounded-2xl border border-amber-100 hover:border-amber-300 transition-all duration-300 hover:shadow-lg">
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-br from-amber-400/0 to-orange-400/0 group-hover:from-amber-400/10 group-hover:to-orange-400/10 transition-all">
                        </div>
                        <div class="relative space-y-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-star text-white text-lg"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="text-3xl md:text-4xl font-black text-slate-900">98%</div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Parent Rating
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIALS ================= -->
    <section class="max-w-7xl mx-auto px-6 py-20 relative">
        <div class="text-center mb-12">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-100 text-purple-700 text-[10px] font-bold uppercase tracking-widest mb-3">
                <i class="fas fa-quote-left"></i>
                Testimonials
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                What Parents Are Saying
            </h2>
            <p class="text-slate-600 text-sm max-w-2xl mx-auto">Real stories from families who've experienced
                transformation</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Testimonial 1 -->
            <div class="glass-card p-8 rounded-3xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-1 mb-4 star-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 italic leading-relaxed mb-6">
                    "YPMMH has been a blessing for our family. My son has grown so much in confidence and his
                    understanding of Islamic values.
                    The mentors are truly dedicated and caring. Alhamdulillah!"
                </p>
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center text-purple-600 font-bold text-sm">
                        AA
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 text-sm">Aisha Abdullahi</div>
                        <div class="text-[10px] text-slate-500">Parent of 3 Children</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="glass-card p-8 rounded-3xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-1 mb-4 star-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 italic leading-relaxed mb-6">
                    "The programs are well-structured and age-appropriate. My daughter looks forward to every session.
                    She's learning practical life skills alongside Islamic teachings. Highly recommended!"
                </p>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 font-bold">
                        IY
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">Ibrahim Yusuf</div>
                        <div class="text-xs text-slate-500">Father of 2</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="glass-card p-8 rounded-3xl hover:shadow-2xl transition-all">
                <div class="flex items-center gap-1 mb-4 star-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 italic leading-relaxed mb-6">
                    "What sets YPMMH apart is their holistic approach. They don't just teach - they mentor and nurture.
                    My children have become more responsible and spiritually aware. May Allah bless the team!"
                </p>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-green-600 font-bold">
                        FM
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">Fatima Mohammed</div>
                        <div class="text-xs text-slate-500">Mother of 4</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= WHAT WE TEACH ================= -->
    <section class="max-w-7xl mx-auto px-6 py-16 relative">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                What We Teach
            </h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Our comprehensive curriculum covers essential life skills and
                values.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Topic Cards -->
            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-blue-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600"><i
                            class="fa-regular fa-id-badge"></i></span>
                    Who Am I?
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Identity, self-awareness, purpose, and confidence building for the future.
                </p>
            </div>

            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-green-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600"><i
                            class="fa-solid fa-leaf"></i></span>
                    Character
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Honesty, resilience, patience, discipline, and emotional intelligence.
                </p>
            </div>

            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-purple-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600"><i
                            class="fa-solid fa-users"></i></span>
                    Relationships
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Social skills, teamwork, leadership, and conflict resolution mastery.
                </p>
            </div>

            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-orange-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600"><i
                            class="fa-solid fa-chart-line"></i></span>
                    Entrepreneurship
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Business thinking, money management, and opportunity creation.
                </p>
            </div>

            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-red-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600"><i
                            class="fa-solid fa-flag"></i></span>
                    Leadership
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Leading with confidence and supporting others effectively.
                </p>
            </div>

            <div class="glass-card p-8 rounded-2xl hover:bg-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -mr-12 -mt-12 transition-all group-hover:bg-teal-100">
                </div>
                <h4 class="font-bold text-lg mb-3 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600"><i
                            class="fa-solid fa-hands-helping"></i></span>
                    Practical Learning
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed relative z-10">
                    Workshops, mentoring, counseling, projects, and community service.
                </p>
            </div>
        </div>
    </section>

    <!-- ================= CONTACT US ================= -->
    <section id="contact" class="max-w-7xl mx-auto px-6 py-20 relative overflow-hidden">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Left Side: Text & Info -->
            <div class="relative z-10">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-[#0B4D73] text-xs font-semibold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#0B4D73] animate-pulse"></span>
                    Get in Touch
                </div>

                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">
                    Have Questions? <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0B4D73] to-cyan-600">Let's
                        Connect.</span>
                </h2>

                <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                    Whether you're a parent looking for guidance or a partner interested in collaboration, we're here to
                    help.
                </p>

                <div class="space-y-5">
                    <div class="flex items-center gap-4 group cursor-pointer">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white shadow-lg shadow-blue-900/5 flex items-center justify-center text-[#0B4D73] group-hover:scale-110 transition-transform duration-300 border border-slate-100">
                            <i class="fa-solid fa-envelope text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 font-medium">Email Us</p>
                            <p class="font-bold text-slate-800 group-hover:text-[#0B4D73] transition-colors">
                                hello@YPMMH.org</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group cursor-pointer">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white shadow-lg shadow-blue-900/5 flex items-center justify-center text-[#0B4D73] group-hover:scale-110 transition-transform duration-300 border border-slate-100">
                            <i class="fa-solid fa-phone text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 font-medium">Call Us</p>
                            <p class="font-bold text-slate-800 group-hover:text-[#0B4D73] transition-colors">+234 800
                                123 4567</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group cursor-pointer">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white shadow-lg shadow-blue-900/5 flex items-center justify-center text-[#0B4D73] group-hover:scale-110 transition-transform duration-300 border border-slate-100">
                            <i class="fa-solid fa-location-dot text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 font-medium">Visit Us</p>
                            <p class="font-bold text-slate-800 group-hover:text-[#0B4D73] transition-colors">Lagos,
                                Nigeria</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Creative Form -->
            <div class="relative">
                <!-- Decorative blobs behind form -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-cyan-200 rounded-full blur-3xl opacity-40"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-200 rounded-full blur-3xl opacity-40"></div>

                <div
                    class="glass p-8 md:p-10 rounded-3xl relative z-10 border border-white/60 shadow-2xl shadow-blue-900/10">
                    <form action="#" method="POST" class="space-y-5">
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">First
                                    Name</label>
                                <input type="text"
                                    class="w-full bg-white/70 border border-slate-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 focus:border-[#0B4D73] transition-all font-medium"
                                    placeholder="John">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Last
                                    Name</label>
                                <input type="text"
                                    class="w-full bg-white/70 border border-slate-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 focus:border-[#0B4D73] transition-all font-medium"
                                    placeholder="Doe">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Email
                                Address</label>
                            <input type="email"
                                class="w-full bg-white/70 border border-slate-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 focus:border-[#0B4D73] transition-all font-medium"
                                placeholder="john@example.com">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Message</label>
                            <textarea rows="4"
                                class="w-full bg-white/70 border border-slate-200 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 focus:border-[#0B4D73] transition-all resize-none font-medium"
                                placeholder="How can we help you?"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-xl text-white font-bold shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group bg-gradient-to-r from-[#0B4D73] to-cyan-700">
                            Send Message
                            <i
                                class="fa-solid fa-paper-plane text-sm group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="max-w-4xl mx-auto px-6 py-20 text-center relative overflow-hidden">
        <div class="glass rounded-3xl p-12 md:p-16 relative overflow-hidden border border-white/60">
            <!-- Decorative elements -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-[#0B4D73] rounded-full opacity-5"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-cyan-500 rounded-full opacity-5"></div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-blue-100/20 to-cyan-100/20 rounded-full blur-3xl">
            </div>

            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 relative z-10">
                Start the Journey Today
            </h2>
            <p class="mt-4 text-slate-600 text-lg max-w-lg mx-auto relative z-10">
                Help raise confident, capable, and purposeful young Muslims
                ready to thrive in Nigeria and beyond.
            </p>

            <a href="{{ route('enroll') }}"
                class="inline-flex items-center gap-3 mt-8 px-10 py-4 rounded-xl text-white font-bold text-lg shadow-xl shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-1 transition-all relative z-10 bg-gradient-to-r from-[#0B4D73] to-cyan-700">
                Enroll Now
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="py-12 border-t border-slate-200 bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 uppercase">{{ app_name() }}</div>
                        <div class="text-[10px] text-slate-500 uppercase">{{ app_tagline() }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-sm text-slate-500">
                    <a href="{{ route('about') }}" class="hover:text-primary transition-colors">About</a>
                    <a href="{{ route('programs.explore') }}"
                        class="hover:text-primary transition-colors">Programmes</a>
                    <a href="{{ route('blog') }}" class="hover:text-primary transition-colors">Blog</a>
                    <a href="{{ route('about') }}#contact" class="hover:text-primary transition-colors">Contact</a>
                </div>

                <p class="text-sm text-slate-500">© {{ date('Y') }} YPMMH. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ================= PROGRAM MODAL ================= -->
    <div id="programModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="modal-backdrop fixed inset-0" onclick="closeProgramModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
                <button onclick="closeProgramModal()"
                    class="absolute top-4 right-4 w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-all z-10">
                    <i class="fas fa-times"></i>
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
                            class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-widest"></span>
                        <span id="modalAge"
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"></span>
                    </div>

                    <h2 id="modalTitle" class="text-2xl font-extrabold text-slate-900 mb-4 tracking-tight"></h2>

                    <div class="prose prose-sm max-w-none text-slate-500 mb-8 leading-relaxed">
                        <p id="modalDesc"></p>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6 border-t border-slate-100">
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Investment</span>
                            <span id="modalPrice" class="text-2xl font-extrabold text-slate-900"></span>
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <a href="{{ route('register') }}"
                                class="flex-1 sm:flex-none px-8 py-3.5 bg-gradient-to-r from-[#0B4D73] to-cyan-700 text-white rounded-xl font-bold uppercase tracking-widest text-xs text-center shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-0.5 transition-all">
                                Enroll Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            menu.classList.toggle('hidden');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }

        // Carousel Scroll
        function scrollCarousel(id, direction) {
            const carousel = document.getElementById(id + 'Carousel');
            const scrollAmount = 360;
            carousel.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        }

        // YouTube Player
        let player;
        /*
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        */

        function onYouTubeIframeAPIReady() { }

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

            title.innerText = program.name;
            desc.innerText = program.description;
            type.innerText = program.type;
            age.innerText = program.cohort_age_min ? `Ages ${program.cohort_age_min} - ${program.cohort_age_max}` : 'All Ages (7+)';
            price.innerText = `₦ ${new Intl.NumberFormat().format(program.price)}`;

            const thumbUrl = program.thumbnail_path ? `/storage/${program.thumbnail_path}` : '';
            img.src = thumbUrl;

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

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeProgramModal();
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Navbar scroll hide/show effect
        let lastScrollTop = 0;
        let scrollThreshold = 50;
        const navbar = document.getElementById('main-header');

        window.addEventListener('scroll', function () {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            // Only trigger fade effect after scrolling past threshold
            if (currentScroll > scrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    // Scrolling DOWN - hide navbar
                    navbar.classList.add('navbar-hidden');
                } else {
                    // Scrolling UP - show navbar
                    navbar.classList.remove('navbar-hidden');
                }
            } else {
                // Near top of page - always show
                navbar.classList.remove('navbar-hidden');
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);

        // Smart Header Logic - Removed to ensure stability
        // Canvas or body overflow-x-hidden should handle the rest.
    </script>

    <!-- Campaign / Announcement Modal -->
    {{-- @include('partials.campaign-modal') --}}

    {{-- PWA Install Prompt --}}
    @include('partials.pwa-install-prompt')

</body>

</html>