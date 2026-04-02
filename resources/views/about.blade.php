@extends('layouts.public')

@section('title', 'About YPMMH | Islamic Mentorship & Children Counselling')
@section('description', 'Learn about our mission to provide Islamic guidance, mentorship, and leadership training for children. Discover our faith-based values and purpose-driven approach to parenting.')
@section('keywords', 'about YPMMH, islamic mentorship, children counselling, muslim youth organization, islamic values, parenting guide')

@section('content')
    <style>
        .float-slow {
            animation: floatSlow 8s ease-in-out infinite;
        }

        .float-fast {
            animation: floatFast 5s ease-in-out infinite;
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-25px) rotate(3deg);
            }
        }

        @keyframes floatFast {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #0B4D73 0%, #06b6d4 50%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .blob {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }

        .glow {
            box-shadow: 0 0 60px rgba(11, 77, 115, 0.15);
        }
    </style>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="relative min-h-[60vh] flex items-center overflow-hidden">
        <!-- Animated Background Blobs -->
        <div
            class="absolute top-20 right-10 w-72 h-72 bg-gradient-to-br from-blue-200/40 to-cyan-200/40 blob float-slow blur-3xl">
        </div>
        <div
            class="absolute bottom-20 left-10 w-64 h-64 bg-gradient-to-tr from-teal-200/40 to-emerald-200/40 blob float-fast blur-3xl">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-blue-100/20 to-cyan-100/20 rounded-full blur-3xl">
        </div>

        <!-- Decorative Vectors -->
        <svg class="absolute top-32 left-20 w-16 h-16 text-blue-200/60 float-fast" viewBox="0 0 100 100"
            fill="currentColor">
            <polygon points="50,5 61,40 98,40 68,62 79,97 50,75 21,97 32,62 2,40 39,40" />
        </svg>
        <svg class="absolute bottom-40 right-32 w-12 h-12 text-teal-200/60 float-slow" viewBox="0 0 100 100"
            fill="currentColor">
            <circle cx="50" cy="50" r="45" />
        </svg>
        <svg class="absolute top-1/3 right-1/4 w-10 h-10 text-amber-200/60 float-fast" viewBox="0 0 100 100"
            fill="currentColor">
            <rect x="20" y="20" width="60" height="60" rx="10" transform="rotate(45 50 50)" />
        </svg>

        <div class="max-w-7xl mx-auto px-6 py-12 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass border border-white/50">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-slate-700 text-xs font-bold uppercase tracking-widest">Established 2023</span>
                    </div>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 leading-[1.1]">
                        Shaping<br>
                        <span class="gradient-text">Future Leaders</span><br>
                        <span class="text-slate-400">with Faith</span>
                    </h1>

                    <p class="text-base md:text-lg text-slate-600 leading-relaxed max-w-xl">
                        We nurture confident, purpose-driven young Muslims through mentorship rooted in
                        <span class="font-bold text-slate-800">Islamic values</span> and
                        <span class="font-bold text-slate-800">modern excellence</span>.
                    </p>

                    <div class="flex flex-wrap gap-3 pt-3">
                        <div
                            class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white/80 backdrop-blur-sm shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all cursor-default text-sm">
                            <div
                                class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-mosque text-sm"></i>
                            </div>
                            <span class="font-bold text-slate-800">Faith-Based</span>
                        </div>
                        <div
                            class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white/80 backdrop-blur-sm shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all cursor-default text-sm">
                            <div
                                class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-blue-600">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <span class="font-bold text-slate-800">5,000+ Students</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Mission & Vision -->
                <div class="space-y-4">
                    <!-- Mission Card -->
                    <div
                        class="glass p-6 rounded-xl glow hover:scale-[1.02] transition-all duration-500 group relative overflow-hidden">
                        <div
                            class="absolute -top-10 -right-10 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-cyan-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-[#0B4D73] to-cyan-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-900/30 group-hover:rotate-6 transition-transform flex-shrink-0">
                                <i class="fas fa-bullseye text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-slate-900 mb-2">Our Mission</h3>
                                <p class="text-slate-600 text-sm leading-relaxed">
                                    To provide a structured, faith-based mentoring environment where young Muslims discover
                                    their purpose, build unshakeable confidence, and develop character.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Vision Card -->
                    <div
                        class="glass p-6 rounded-xl glow hover:scale-[1.02] transition-all duration-500 group relative overflow-hidden">
                        <div
                            class="absolute -bottom-10 -left-10 w-32 h-32 bg-gradient-to-tr from-teal-400/20 to-emerald-400/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative flex items-start gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-teal-900/30 group-hover:rotate-6 transition-transform flex-shrink-0">
                                <i class="fas fa-eye text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-slate-900 mb-2">Our Vision</h3>
                                <p class="text-slate-600 text-sm leading-relaxed">
                                    A world where every young Muslim is empowered with self-knowledge, spiritual grounding,
                                    and practical skills to become a positive changemaker.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== WHAT WE DO SECTION ==================== -->
    <section class="relative py-20 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-50/50 to-transparent"></div>
        <div
            class="absolute left-0 top-1/2 -translate-y-1/2 w-1/3 h-72 bg-gradient-to-r from-red-100/30 to-transparent rounded-r-full blur-3xl">
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Emotional Appeal -->
                <div class="space-y-5">
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">
                        Parenting in the<br>Digital Age is
                        <span class="relative inline-block">
                            <span class="text-red-500">Hard</span>
                            <svg class="absolute -bottom-2 left-0 w-full" height="8" viewBox="0 0 100 8"
                                preserveAspectRatio="none">
                                <path d="M0,4 Q25,0 50,4 T100,4" stroke="#fca5a5" stroke-width="3" fill="none" />
                            </svg>
                        </span>
                    </h2>

                    <div class="space-y-4 text-base text-slate-600 leading-relaxed">
                        <p>
                            Screens are <span class="font-bold text-slate-800">everywhere</span>. Values are shifting. As a
                            parent, you're competing for your child's heart against millions of influencers.
                        </p>
                        <p class="text-base font-medium text-slate-800 italic border-l-4 border-red-300 pl-4">
                            "Will they hold onto their faith? Will they have strong character? Who are their role models?"
                        </p>
                        <p>
                            We get it. That's why we built YPMMH. We don't just "teach" — we <span
                                class="font-bold text-[#0B4D73]">partner with you</span> to raise confident, resilient
                            Muslim leaders.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-3">
                        <div
                            class="px-6 py-3 rounded-full bg-red-50 text-red-700 font-bold border-2 border-red-200 hover:bg-red-100 transition-colors">
                            <i class="fas fa-shield-alt mr-2"></i>Safe Space
                        </div>
                        <div
                            class="px-6 py-3 rounded-full bg-green-50 text-green-700 font-bold border-2 border-green-200 hover:bg-green-100 transition-colors">
                            <i class="fas fa-seedling mr-2"></i>Growth Mindset
                        </div>
                        <div
                            class="px-6 py-3 rounded-full bg-blue-50 text-blue-700 font-bold border-2 border-blue-200 hover:bg-blue-100 transition-colors">
                            <i class="fas fa-heart mr-2"></i>Islamic Values
                        </div>
                    </div>
                </div>

                <!-- Right: Rolling Programs -->
                <div class="relative">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-blue-100/50 to-indigo-100/50 rounded-2xl -rotate-3 blur-sm">
                    </div>
                    <div class="glass p-6 rounded-xl border border-blue-100 shadow-lg relative">
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <p class="text-[#0B4D73] font-black text-xs uppercase tracking-widest mb-1">Our Methodology
                                </p>
                                <h3 class="text-lg font-black text-slate-900">Rolling Programs</h3>
                            </div>
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-[#0B4D73] to-cyan-600 text-white rounded-lg flex items-center justify-center text-lg shadow-md flex-shrink-0">
                                <i class="fas fa-sync-alt fa-spin" style="animation-duration: 4s;"></i>
                            </div>
                        </div>

                        <p class="text-sm text-slate-600 leading-relaxed mb-5">
                            We deliver exactly what your child needs to know,
                            <span
                                class="inline-block px-3 py-1 bg-amber-100 rounded-lg font-black text-amber-800">when</span>
                            they need to know it.
                        </p>

                        <div class="space-y-3">
                            <div
                                class="flex items-start gap-3 p-3 bg-white/60 rounded-lg hover:bg-white transition-colors text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Developmentally Appropriate</h4>
                                    <p class="text-slate-500 text-xs">Topics evolve naturally as your child grows.</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-3 p-3 bg-white/60 rounded-lg hover:bg-white transition-colors text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Flexible Entry</h4>
                                    <p class="text-slate-500 text-xs">Join anytime without feeling "behind".</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-3 p-3 bg-white/60 rounded-lg hover:bg-white transition-colors text-sm">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Continuous Mentorship</h4>
                                    <p class="text-slate-500 text-xs">A reliable support system through crucial years.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== OUR STORY SECTION ==================== -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-teal-50/50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="glass rounded-2xl p-6 md:p-10 shadow-lg relative overflow-hidden">
                <!-- Decorative -->
                <div
                    class="absolute -top-20 -right-20 w-60 h-60 bg-gradient-to-br from-blue-200/30 to-cyan-200/30 rounded-full blur-3xl">
                </div>
                <div
                    class="absolute -bottom-20 -left-20 w-60 h-60 bg-gradient-to-tr from-teal-200/30 to-emerald-200/30 rounded-full blur-3xl">
                </div>

                <div class="grid lg:grid-cols-2 gap-10 items-center relative">
                    <!-- Image -->
                    <div class="relative group">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-[#0B4D73]/20 to-cyan-500/20 rounded-[2rem] blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="https://images.unsplash.com/photo-1584286595398-a59f21d313f5?auto=format&fit=crop&q=80&w=800"
                            alt="Group discussion" class="relative rounded-xl w-full h-[300px] object-cover shadow-lg">
                        <div class="absolute -bottom-4 -right-4 bg-white px-5 py-2 rounded-lg shadow-md text-sm">
                            <p class="text-xl font-black text-[#0B4D73]">2023</p>
                            <p class="text-slate-500 text-xs font-medium">Founded</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-5">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-50 border border-teal-200">
                            <i class="fas fa-book-open text-teal-600"></i>
                            <span class="text-teal-700 text-sm font-bold uppercase tracking-widest">Our Journey</span>
                        </div>

                        <h2 class="text-2xl md:text-3xl font-black text-slate-900">How We Started</h2>

                        <div class="space-y-3 text-sm text-slate-600 leading-relaxed">
                            <p>
                                YPMMH began with a simple observation: many talented young Muslims were struggling to
                                balance
                                their faith identity with the demands of the modern world.
                            </p>
                            <p>
                                In 2023, a diverse team of educators, counselors, and community leaders came together to
                                bridge this gap with a curriculum that doesn't just preach, but <span
                                    class="font-bold text-slate-800">truly engages</span>.
                            </p>
                        </div>

                        <div class="p-5 bg-gradient-to-r from-[#0B4D73] to-cyan-700 rounded-lg text-white">
                            <i class="fas fa-quote-left text-lg text-white/30 mb-2"></i>
                            <p class="text-sm font-medium italic leading-relaxed">
                                "Today, we are proud to have mentored over 5,000 students worldwide, seeing transformative
                                results in their confidence, academic performance, and spiritual connection."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== DIRECTOR'S MESSAGE SECTION ==================== -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-amber-50/30 to-transparent"></div>
        <div
            class="absolute top-20 right-20 w-80 h-80 bg-gradient-to-br from-amber-200/30 to-orange-200/30 rounded-full blur-3xl float-slow">
        </div>
        <div
            class="absolute bottom-20 left-20 w-64 h-64 bg-gradient-to-tr from-blue-200/30 to-cyan-200/30 rounded-full blur-3xl float-fast">
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-5 gap-10 items-center">
                <!-- Left: Director Photo -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <!-- Glow Effect -->
                        <div
                            class="absolute -inset-8 bg-gradient-to-br from-amber-400/20 via-orange-400/20 to-red-400/20 rounded-[3rem] blur-2xl">
                        </div>

                        <!-- Photo Container -->
                        <div class="relative bg-white rounded-xl p-2 shadow-md">
                            <div
                                class="aspect-[3/4] rounded-lg overflow-hidden bg-gradient-to-br from-blue-50 to-cyan-50 flex items-center justify-center">
                                <!-- Replace with actual photo -->
                                <i class="fas fa-user-tie text-7xl text-slate-300"></i>
                            </div>

                            <!-- Quote Badge -->
                            <div
                                class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg border-3 border-white">
                                <i class="fas fa-quote-right text-white text-xl"></i>
                            </div>
                        </div>

                        <!-- Director Info -->
                        <div class="text-center mt-5 space-y-1">
                            <h3 class="text-lg font-black text-slate-900">Fatimoh Samuel</h3>
                            <p class="text-slate-500 text-xs">(Ummu Amin)</p>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 text-xs">
                                <i class="fas fa-crown text-amber-500 text-xs"></i>
                                <span class="text-[#0B4D73] font-bold uppercase tracking-widest">Founding Director</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Message -->
                <div class="lg:col-span-3 space-y-4">
                    <div
                        class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200">
                        <i class="fas fa-message text-amber-600"></i>
                        <span class="text-amber-700 text-sm font-bold uppercase tracking-widest">A Message from Our
                            Director</span>
                    </div>

                    <blockquote class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                        <span class="text-4xl text-amber-200">"</span><br>
                        Every child is a treasure, a trust from Allah.
                    </blockquote>

                    <p class="text-base text-slate-600 leading-relaxed">
                        Our mission is to help unlock their potential while keeping their hearts connected to their Creator.
                    </p>

                    <div class="space-y-3 text-sm text-slate-600 leading-relaxed">
                        <p>
                            <span class="font-bold text-slate-900 text-sm">Assalamu Alaikum wa Rahmatullahi wa
                                Barakatuhu,</span>
                        </p>
                        <p>
                            Welcome to YPMMH. As a parent myself, I understand the challenges of raising confident,
                            God-conscious children in today's fast-paced world. That's why we created this safe space where
                            young Muslims can learn, grow, and thrive.
                        </p>
                        <p>
                            Our programmes blend Islamic values with practical life skills, preparing children not just for
                            academic success, but for a <span class="font-bold text-slate-800">purposeful life</span> that
                            benefits themselves, their families, and the Ummah.
                        </p>
                        <p class="text-sm font-bold text-slate-900">
                            Together, let's raise a generation that makes us proud—in this life and the next,
                            <span class="text-emerald-600 italic">InshaAllah.</span>
                        </p>
                    </div>

                    <!-- Signature -->
                    <div class="pt-4 border-t-2 border-amber-100 flex items-center gap-3">
                        <div class="text-2xl font-['Brush_Script_MT',cursive] text-[#0B4D73]">Ummu Amin</div>
                        <div class="flex gap-2">
                            <a href="{{ \App\Models\Setting::get('social_linkedin', '#') }}"
                                class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-[#0B4D73] hover:text-white flex items-center justify-center text-[#0B4D73] transition-all shadow-sm text-sm">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}"
                                class="w-9 h-9 rounded-lg bg-blue-50 hover:bg-[#0B4D73] hover:text-white flex items-center justify-center text-[#0B4D73] transition-all shadow-sm text-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TEAM SECTION ==================== -->
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 border border-purple-200 mb-3 text-xs">
                    <i class="fas fa-users text-purple-600 text-xs"></i>
                    <span class="text-purple-700 font-bold uppercase tracking-widest">Our Team</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-3">Meet Our Mentors</h2>
                <p class="text-base text-slate-500 max-w-2xl mx-auto">World-class educators, entrepreneurs, and spiritual
                    guides dedicated to your child's growth.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Member 1 -->
                <div
                    class="glass-card p-5 rounded-xl text-center group hover:shadow-lg hover:-translate-y-1 transition-all duration-500">
                    <div
                        class="w-24 h-24 mx-auto rounded-lg mb-4 overflow-hidden border-3 border-white shadow-md group-hover:scale-105 transition-transform">
                        <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300"
                            class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Dr. Ahmed Ali</h4>
                    <p class="text-[#0B4D73] font-bold text-[10px] uppercase tracking-widest mb-1 mt-0.5">Lead Mentor</p>
                    <p class="text-slate-500 text-xs">PhD in Education with 15 years of youth mentoring experience.</p>
                </div>

                <!-- Member 2 -->
                <div
                    class="glass-card p-5 rounded-xl text-center group hover:shadow-lg hover:-translate-y-1 transition-all duration-500">
                    <div
                        class="w-24 h-24 mx-auto rounded-lg mb-4 overflow-hidden border-3 border-white shadow-md group-hover:scale-105 transition-transform">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300"
                            class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Sarah Jameel</h4>
                    <p class="text-[#0B4D73] font-bold text-[10px] uppercase tracking-widest mb-1 mt-0.5">Counselor</p>
                    <p class="text-slate-500 text-xs">Certified youth counselor specializing in teen confidence.</p>
                </div>

                <!-- Member 3 -->
                <div
                    class="glass-card p-5 rounded-xl text-center group hover:shadow-lg hover:-translate-y-1 transition-all duration-500">
                    <div
                        class="w-24 h-24 mx-auto rounded-lg mb-4 overflow-hidden border-3 border-white shadow-md group-hover:scale-105 transition-transform">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300"
                            class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Bilal Khan</h4>
                    <p class="text-[#0B4D73] font-bold text-[10px] uppercase tracking-widest mb-1 mt-0.5">Entrepreneur</p>
                    <p class="text-slate-500 text-xs">Tech founder helping students develop business thinking.</p>
                </div>

                <!-- Member 4 -->
                <div
                    class="glass-card p-5 rounded-xl text-center group hover:shadow-lg hover:-translate-y-1 transition-all duration-500">
                    <div
                        class="w-24 h-24 mx-auto rounded-lg mb-4 overflow-hidden border-3 border-white shadow-md group-hover:scale-105 transition-transform">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300"
                            class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Amina Yusuf</h4>
                    <p class="text-[#0B4D73] font-bold text-[10px] uppercase tracking-widest mb-1 mt-0.5">Community Lead</p>
                    <p class="text-slate-500 text-xs">Passionate about connecting youth with service opportunities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CONTACT SECTION ==================== -->
    <section id="contact" class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-50/50 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 mb-3 text-xs">
                    <i class="fas fa-headset text-[#0B4D73] text-xs"></i>
                    <span class="text-[#0B4D73] font-bold uppercase tracking-widest">Get In Touch</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-3">
                    Contact <span class="gradient-text">Us</span>
                </h2>
                <p class="text-base text-slate-500 max-w-2xl mx-auto">We'd love to hear from you! Reach out through any
                    channel below.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Email -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-[#0B4D73] mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">Email Us</h4>
                    <p class="text-slate-500 mb-2 text-xs">For general inquiries and support</p>
                    <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'hello@YPMMH.org') }}"
                        class="text-[#0B4D73] font-bold text-xs hover:underline">{{
                        \App\Models\Setting::get('contact_email', 'hello@YPMMH.org') }}</a>
                </div>

                <!-- Phone -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center text-green-600 mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-phone text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">Call Us</h4>
                    <p class="text-slate-500 mb-2 text-xs">Speak with our team directly</p>
                    <a href="tel:{{ \App\Models\Setting::get('contact_phone', '+2348001234567') }}"
                        class="text-[#0B4D73] font-bold text-xs hover:underline">{{ \App\Models\Setting::get('contact_phone', '+234 800 123 4567') }}</a>
                </div>

                <!-- WhatsApp -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-green-100 to-lime-100 flex items-center justify-center text-green-500 mb-3 group-hover:scale-110 transition-transform">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">WhatsApp</h4>
                    <p class="text-slate-500 mb-2 text-xs">Quick questions? Chat with us</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('contact_whatsapp', '2348001234567')) }}"
                        target="_blank"
                        class="text-[#0B4D73] font-bold text-xs hover:underline">{{ \App\Models\Setting::get('contact_whatsapp', '+234 800 123 4567') }}</a>
                </div>

                <!-- Location -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center text-red-500 mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-location-dot text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1">Visit Us</h4>
                    <p class="text-slate-500 mb-2 text-xs">Come meet us in person</p>
                    <p class="text-slate-700 font-bold text-xs">
                        {{ \App\Models\Setting::get('contact_address', 'Lagos, Nigeria') }}
                    </p>
                </div>

                <!-- Office Hours -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-purple-100 to-violet-100 flex items-center justify-center text-purple-600 mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-2">Office Hours</h4>
                    <div class="space-y-0.5 text-slate-600 text-xs">
                        <p><span class="font-bold">Mon - Fri:</span>
                            {{ \App\Models\Setting::get('office_hours_weekdays', '9AM - 5PM') }}</p>
                        <p><span class="font-bold">Saturday:</span>
                            {{ \App\Models\Setting::get('office_hours_saturday', '10AM - 2PM') }}</p>
                        <p class="text-slate-400"><span class="font-bold">Sunday:</span>
                            {{ \App\Models\Setting::get('office_hours_sunday', 'Closed') }}</p>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="glass p-5 rounded-xl hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div
                        class="w-14 h-14 rounded-lg bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center text-pink-500 mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-share-alt text-lg"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-2">Follow Us</h4>
                    <div class="flex gap-2">
                        <a href="{{ \App\Models\Setting::get('social_facebook', '#') }}"
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center hover:shadow-lg hover:scale-110 transition-all">
                            <i class="fab fa-facebook text-sm"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('social_instagram', '#') }}"
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-pink-400 to-red-500 text-white flex items-center justify-center hover:shadow-lg hover:scale-110 transition-all">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}"
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-sky-400 to-blue-500 text-white flex items-center justify-center hover:shadow-lg hover:scale-110 transition-all">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('social_youtube', '#') }}"
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center hover:shadow-lg hover:scale-110 transition-all">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-12 glass rounded-xl p-6 md:p-10 text-center relative overflow-hidden">
                <div class="absolute -top-20 -left-20 w-40 h-40 bg-[#0B4D73] rounded-full opacity-5"></div>
                <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-cyan-500 rounded-full opacity-5"></div>

                <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-3 relative z-10">Ready to Get Started?</h3>
                <p class="text-base text-slate-600 mb-6 max-w-2xl mx-auto relative z-10">
                    Have questions about our programmes or want to enroll your child? We're here to help you every step of
                    the way.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
                    <a href="{{ route('enroll') }}"
                        class="px-6 py-3 rounded-lg text-white font-bold text-sm shadow-md shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-0.5 transition-all flex items-center gap-2 bg-gradient-to-r from-[#0B4D73] to-cyan-700">
                        <i class="fas fa-user-plus"></i>
                        Enroll Now
                    </a>
                    <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'hello@YPMMH.org') }}"
                        class="px-6 py-3 rounded-lg bg-white border-2 border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center gap-2">
                        <i class="fas fa-envelope text-[#0B4D73]"></i>
                        Send Email
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection