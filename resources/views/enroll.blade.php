@extends('layouts.public')

@section('title', 'Enrollment Information - YPMMH')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-20">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24 space-y-6 animate-fade-in">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass border border-blue-100 text-[#0B4D73] text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                <i class="fas fa-graduation-cap"></i>
                Start Your Journey
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-[1.1]">
                Begin Your <br>
                <span class="gradient-text">Growth Odyssey</span>
            </h1>
            <p class="text-lg text-slate-500 font-medium leading-relaxed">
                Choose the enrollment path that fits you best. Whether you're a parent investing in your child's future or a
                teen ready to lead, your path begins here.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 md:gap-12 max-w-5xl mx-auto mb-24 md:mb-32">
            <!-- Parent Path -->
            <div
                class="glass p-8 sm:p-12 rounded-[2.5rem] border border-white shadow-xl shadow-blue-900/5 group hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center relative overflow-hidden">
                <div
                    class="absolute -top-24 -right-24 w-48 h-48 bg-blue-50/50 rounded-full blur-3xl group-hover:bg-blue-100/50 transition-colors">
                </div>

                <div
                    class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0B4D73] text-3xl mb-10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-sm relative z-10">
                    <i class="fas fa-user-shield"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-6 relative z-10">Parent / Guardian</h3>
                <p class="text-slate-600 text-base leading-relaxed mb-10 font-bold opacity-80 relative z-10">
                    Register as a parent to manage your children's enrollments, track their spiritual and academic progress,
                    and get exclusive parenting insights.
                </p>

                <ul class="space-y-4 mb-12 text-left w-full relative z-10">
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Register multiple children easily</span>
                    </li>
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Track milestones & impact stats</span>
                    </li>
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Exclusive parent-mentor circles</span>
                    </li>
                </ul>

                <a href="{{ route('register') }}"
                    class="mt-auto w-full py-5 bg-[#0B4D73] text-white text-center rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-blue-900/20 hover:bg-slate-900 hover:shadow-2xl transition-all relative z-10">
                    Register as Parent
                </a>
            </div>

            <!-- Participant Path -->
            <div
                class="glass p-8 sm:p-12 rounded-[2.5rem] border border-white shadow-xl shadow-purple-900/5 group hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center relative overflow-hidden">
                <div
                    class="absolute -top-24 -right-24 w-48 h-48 bg-purple-50/50 rounded-full blur-3xl group-hover:bg-purple-100/50 transition-colors">
                </div>

                <div
                    class="w-20 h-20 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-3xl mb-10 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 shadow-sm relative z-10">
                    <i class="fas fa-rocket"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-6 relative z-10">Young Adult (18+)</h3>
                <p class="text-slate-600 text-base leading-relaxed mb-10 font-bold opacity-80 relative z-10">
                    Start your own journey! Join community circles, attend live workshops, and discover your unique purpose
                    through faith-based mentorship.
                </p>

                <ul class="space-y-4 mb-12 text-left w-full relative z-10">
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-purple-100/50 text-purple-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Join the Global Community Hub</span>
                    </li>
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-purple-100/50 text-purple-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Earn real-world medals & ranks</span>
                    </li>
                    <li class="flex items-start gap-4 text-slate-700 font-bold text-xs uppercase tracking-tight">
                        <div
                            class="w-5 h-5 rounded-full bg-purple-100/50 text-purple-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-[10px]"></i>
                        </div>
                        <span>Access live mentoring events</span>
                    </li>
                </ul>

                <a href="{{ route('register') }}"
                    class="mt-auto w-full py-5 bg-purple-600 text-white text-center rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-purple-900/20 hover:bg-slate-900 hover:shadow-2xl transition-all relative z-10">
                    Register as Participant
                </a>
            </div>
        </div>

        <!-- Support Section -->
        <div class="max-w-4xl mx-auto">
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-blue-100/50 to-purple-100/50 rounded-[3rem] blur opacity-50 transition duration-1000 group-hover:opacity-100">
                </div>
                <div
                    class="relative bg-white/70 backdrop-blur-xl rounded-[2.5rem] border border-white p-8 md:p-12 text-center shadow-xl">
                    <h4 class="text-xl font-black text-slate-900 mb-4">Need specific guidance?</h4>
                    <p class="text-slate-500 font-bold text-sm leading-relaxed max-w-2xl mx-auto mb-10">
                        If you're unsure which path to take or have questions about our curriculum, our mentoring advisors
                        are ready to help.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 md:gap-12">
                        <a href="mailto:hello@YPMMH.org"
                            class="flex items-center gap-3 font-black text-slate-900 hover:text-blue-600 transition-all text-xs uppercase tracking-widest px-6 py-3 rounded-xl bg-blue-50/50 border border-blue-100">
                            <i class="fas fa-envelope text-blue-600"></i> hello@YPMMH.org
                        </a>
                        <a href="tel:+2348001234567"
                            class="flex items-center gap-3 font-black text-slate-900 hover:text-blue-600 transition-all text-xs uppercase tracking-widest px-6 py-3 rounded-xl bg-blue-50/50 border border-blue-100">
                            <i class="fas fa-phone-alt text-blue-600"></i> +234 800 123 4567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection