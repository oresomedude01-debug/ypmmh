@extends('layouts.dashboard')

@section('title', 'Family Overview')

@section('styles')
    <style>
        .child-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .child-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0,0,0,0.1);
        }
        .stat-badge {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-fade-in pb-10">
    <!-- Premium Compact Header -->
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#0B4D73] to-[#04334d] p-6 md:p-8 text-white shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-black mb-2 tracking-tight">Assalamu Alaikum, {{ $parent->first_name }}</h1>
            <p class="text-blue-100/80 text-sm font-medium max-w-xl leading-relaxed">
                Welcome to your family's growth hub. Monitor progress, read observations, and download reports.
            </p>
            
            <div class="flex flex-wrap gap-3 mt-6">
                <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 flex items-center gap-2">
                    <i class="fas fa-children text-yellow-400 text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $children->count() }} Mentees Enrolled</span>
                </div>
            </div>
        </div>
    </div>

    @if($children->contains(fn($c) => !$c->hasPremiumAccess()))
    <!-- Premium Reminder Banner -->
    <div class="bg-gradient-to-r from-yellow-500 to-amber-600 rounded-[2rem] p-6 text-white shadow-lg shadow-yellow-500/30 relative overflow-hidden mt-6 mb-2 animate-slide-up">
        <i class="fas fa-crown absolute opacity-10 -right-4 -bottom-4 text-[100px]"></i>
        <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-black mb-1 flex items-center gap-2">
                    <i class="fas fa-lock text-yellow-100"></i> Unlock Core Paths
                </h3>
                <p class="text-sm font-medium text-yellow-100">One or more of your mentees need Premium Access to unlock their auto-assigned Core Path (Rolling) programmes.</p>
            </div>
            <a href="{{ route('premium.subscribe') }}" class="px-6 py-3 bg-white text-yellow-600 rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all whitespace-nowrap">
                Subscribe Now
            </a>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
        <!-- Children Progress Cards -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-viewfinder text-[#0B4D73]"></i>
                    Mentee Overview
                </h2>
                <a href="{{ route('parent.children.create') }}" class="px-5 py-2.5 bg-[#0B4D73] text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/10 hover:bg-slate-900 transition-all flex items-center gap-2">
                    <i class="fas fa-plus text-[9px]"></i>
                    Add Mentee
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($children as $child)
                    <div class="child-card glass rounded-[2rem] p-6 border border-white shadow-lg flex flex-col">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-[#0B4D73]/10 shrink-0">
                                @if($child->profile_picture)
                                    <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xl font-black text-[#0B4D73]">
                                        {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xl font-black text-slate-900 truncate">{{ $child->first_name }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-blue-50 text-[#0B4D73] text-[9px] font-black uppercase rounded-md tracking-tighter">Level {{ floor($child->xp_points / 100) + 1 }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 capitalize">{{ $child->rank }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 flex-1">
                            <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Overall Progress</span>
                                    <span class="text-sm font-black text-[#0B4D73]">{{ $child->lessonProgress()->whereNotNull('completed_at')->count() }} Lessons</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    @php 
                                        $totalLessons = $child->enrollments()->withCount(['program as lessons_count' => function($q) {
                                            $q->withCount('contents');
                                        }])->get()->sum('lessons_count');
                                        $completedLessons = $child->lessonProgress()->whereNotNull('completed_at')->count();
                                        $percent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-[#0B4D73] to-blue-500 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <div class="flex-1 p-3 bg-white rounded-xl border border-slate-100 text-center">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">XP Points</p>
                                    <p class="text-lg font-black text-slate-900 leading-none">{{ $child->xp_points }}</p>
                                </div>
                                <div class="flex-1 p-3 bg-white rounded-xl border border-slate-100 text-center">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Medals</p>
                                    <p class="text-lg font-black text-orange-500 leading-none">{{ $child->achievements->count() }}</p>
                                </div>
                            </div>

                            <!-- Program Management Toggle -->
                            <div class="pt-4 border-t border-slate-50">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Enrolled Programs</p>
                                <div class="space-y-2">
                                    @foreach($child->enrollments as $enrollment)
                                        @if(!$enrollment->program)
                                            @continue
                                        @endif
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group/prog">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-2 h-2 rounded-full {{ $enrollment->is_active ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-slate-300' }}"></div>
                                                <span class="text-xs font-bold text-slate-700 truncate">{{ $enrollment->program->name }}</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                @if($enrollment->program->type === 'offline')
                                                    <a href="{{ route('parent.programs.pass', [$enrollment->program->id, $child->id]) }}" 
                                                    target="_blank"
                                                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-all">
                                                        <span>Print Pass</span>
                                                        <i class="fas fa-print text-[7px]"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('parent.enrollments.toggle', $enrollment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-tighter transition-all {{ $enrollment->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                                        <span>{{ $enrollment->is_active ? 'Active' : 'Paused' }}</span>
                                                        <i class="fas {{ $enrollment->is_active ? 'fa-pause' : 'fa-play' }} text-[7px]"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('parent.children.show', $child->id) }}" class="mt-6 w-full py-4 bg-[#0B4D73] text-white rounded-2xl flex items-center justify-center gap-3 font-black uppercase tracking-widest text-[10px] hover:bg-slate-900 transition-all shadow-lg shadow-blue-900/10 group">
                            <span>Deep Report</span>
                            <i class="fas fa-chevron-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity & Observations -->
        <div class="space-y-6">
            <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                <i class="fas fa-glasses text-[#0B4D73]"></i>
                Latest Observations
            </h2>

            <div class="space-y-4">
                @forelse($recentObservations as $obs)
                    <div class="glass rounded-2xl p-5 border border-white shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[10px] font-black text-[#0B4D73] border border-blue-100 overflow-hidden shrink-0">
                                @if($obs->mentor->profile_picture)
                                    <img src="{{ asset('storage/' . $obs->mentor->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($obs->mentor->first_name, 0, 1) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-slate-900 truncate">{{ $obs->mentor->full_name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $obs->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[8px] font-black uppercase rounded-md tracking-widest mb-1 inline-block">Refers to {{ $obs->child->first_name }}</span>
                            <p class="text-xs text-slate-600 line-clamp-3 italic leading-relaxed">"{{ $obs->content }}"</p>
                        </div>
                        <a href="{{ route('parent.children.show', $obs->child_id) }}" class="text-[9px] font-black text-[#0B4D73] uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                            Read Full Context <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @empty
                    <div class="glass rounded-2xl p-10 text-center border-2 border-dashed border-slate-200">
                        <i class="fas fa-sticky-note text-3xl text-slate-200 mb-3"></i>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">No observations yet</p>
                    </div>
                @endforelse
            </div>

            <!-- Quick Stats -->
            <div class="glass rounded-[2rem] p-6 border border-white shadow-lg bg-gradient-to-br from-[#0B4D73] to-[#04334d] text-white">
                <h3 class="text-sm font-black uppercase tracking-widest mb-4 opacity-70">Learning Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold">Total Lessons Finished</span>
                        <span class="text-lg font-black">{{ $children->sum(fn($c) => $c->lessonProgress()->whereNotNull('completed_at')->count()) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold">Total Medals Earned</span>
                        <span class="text-lg font-black text-yellow-400">{{ $children->sum(fn($c) => $c->achievements->count()) }}</span>
                    </div>
                </div>
            </div>
            <!-- Feedback CTA -->
            <div class="glass rounded-[2rem] p-6 border border-amber-100 shadow-lg bg-gradient-to-br from-amber-50 to-white">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shadow-sm">
                        <i class="fas fa-comment-dots text-xs"></i>
                    </div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Help us improve</h3>
                </div>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">Have a suggestion or found an issue? We'd love to hear from you.</p>
                <button onclick="openFeedbackModal()" class="w-full py-3 bg-white border border-amber-200 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                    Send Feedback
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Welcome Onboarding Modal --}}
@if(session()->pull('show_welcome_modal'))
<div id="welcomeModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in relative">
        {{-- Close Button --}}
        <button onclick="closeWelcomeModal()" class="absolute top-5 right-5 z-10 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all">
            <i class="fas fa-times text-xs"></i>
        </button>

        {{-- Slides Container --}}
        <div id="onboarding-slides">
            {{-- Slide 1: Welcome --}}
            <div class="onboarding-slide" data-slide="1">
                <div class="bg-gradient-to-br from-[#0B4D73] to-[#04334d] p-8 pb-10 text-center text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-400/10 rounded-full blur-2xl -ml-10 -mb-10"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 mx-auto bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center mb-5 border border-white/20">
                            <i class="fas fa-hands-holding-child text-4xl text-yellow-400"></i>
                        </div>
                        <h2 class="text-2xl font-black mb-2 tracking-tight">Welcome, {{ auth()->user()->first_name }}! 🎉</h2>
                        <p class="text-blue-100/80 text-sm font-medium max-w-xs mx-auto leading-relaxed">Your family's growth journey starts here. Let us show you how to get started.</p>
                    </div>
                </div>
                <div class="p-8 text-center">
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-4 p-4 bg-blue-50/80 rounded-2xl text-left border border-blue-100">
                            <div class="w-10 h-10 rounded-xl bg-[#0B4D73] text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-child-reaching text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Step 1</p>
                                <p class="text-[11px] text-slate-500 font-medium">Add your children (mentees) to the platform</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-emerald-50/80 rounded-2xl text-left border border-emerald-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-book-open text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Step 2</p>
                                <p class="text-[11px] text-slate-500 font-medium">Browse & enroll them in learning programs</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-amber-50/80 rounded-2xl text-left border border-amber-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-chart-line text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Step 3</p>
                                <p class="text-[11px] text-slate-500 font-medium">Track their progress, view observations & reports</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="showSlide(2)" class="w-full py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-700 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl hover:shadow-lg hover:shadow-blue-900/20 transition-all active:scale-[0.98]">
                        Show Me How <i class="fas fa-arrow-right ml-2 text-[9px]"></i>
                    </button>
                </div>
            </div>

            {{-- Slide 2: How to Add a Mentee --}}
            <div class="onboarding-slide hidden" data-slide="2">
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-8 text-center text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 border border-white/20">
                            <i class="fas fa-user-plus text-3xl text-emerald-200"></i>
                        </div>
                        <h2 class="text-xl font-black mb-1 tracking-tight">Adding a Mentee</h2>
                        <p class="text-emerald-100/80 text-xs font-medium">Here's how to register your children</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-5 mb-8">
                        {{-- Step visual guide --}}
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-[#0B4D73] text-white flex items-center justify-center text-xs font-black shrink-0">1</div>
                                <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                            </div>
                            <div class="pb-5">
                                <p class="font-black text-sm text-slate-800 mb-1">Click "Add Mentee"</p>
                                <p class="text-xs text-slate-500 leading-relaxed">On your dashboard, click the <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#0B4D73] text-white rounded-md text-[9px] font-bold"><i class="fas fa-plus text-[7px]"></i> Add Mentee</span> button in the Mentee Overview section.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-[#0B4D73] text-white flex items-center justify-center text-xs font-black shrink-0">2</div>
                                <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                            </div>
                            <div class="pb-5">
                                <p class="font-black text-sm text-slate-800 mb-1">Fill in Their Details</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Enter their name, date of birth, gender, and an email address. Upload a profile photo if you'd like!</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black shrink-0">
                                    <i class="fas fa-check text-[10px]"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-black text-sm text-slate-800 mb-1">Done!</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Your mentee appears on your dashboard. You can now enroll them in programs.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="showSlide(1)" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all text-[10px] uppercase tracking-widest border border-slate-200">
                            <i class="fas fa-arrow-left mr-1 text-[8px]"></i> Back
                        </button>
                        <button onclick="showSlide(3)" class="flex-[2] py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-700 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl hover:shadow-lg transition-all active:scale-[0.98]">
                            What About Programs? <i class="fas fa-arrow-right ml-1 text-[9px]"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Slide 3: Explore & Enroll --}}
            <div class="onboarding-slide hidden" data-slide="3">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-8 text-center text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4 border border-white/20">
                            <i class="fas fa-graduation-cap text-3xl text-amber-200"></i>
                        </div>
                        <h2 class="text-xl font-black mb-1 tracking-tight">Enrolling in Programs</h2>
                        <p class="text-amber-100/80 text-xs font-medium">Find the perfect learning path</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-5 mb-8">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-[#0B4D73] text-white flex items-center justify-center text-xs font-black shrink-0">1</div>
                                <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                            </div>
                            <div class="pb-5">
                                <p class="font-black text-sm text-slate-800 mb-1">Open Program Catalog</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Navigate to <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-800 text-white rounded-md text-[9px] font-bold"><i class="fas fa-book-open text-[7px]"></i> Programs</span> from the sidebar menu or bottom navigation.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-[#0B4D73] text-white flex items-center justify-center text-xs font-black shrink-0">2</div>
                                <div class="w-0.5 h-full bg-slate-200 mt-1"></div>
                            </div>
                            <div class="pb-5">
                                <p class="font-black text-sm text-slate-800 mb-1">Pick a Program</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Browse available programs suited to your child's age. Click <span class="font-bold text-emerald-600">"Enroll"</span> to select which child to register.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-black shrink-0">
                                    <i class="fas fa-check text-[10px]"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-black text-sm text-slate-800 mb-1">Track Progress</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Once enrolled, track lessons, XP, medals, and mentor observations right from your dashboard!</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="showSlide(2)" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all text-[10px] uppercase tracking-widest border border-slate-200">
                            <i class="fas fa-arrow-left mr-1 text-[8px]"></i> Back
                        </button>
                        <a href="{{ route('parent.children.create') }}" class="flex-[2] py-4 bg-gradient-to-r from-emerald-500 to-emerald-700 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl hover:shadow-lg transition-all active:scale-[0.98] text-center shadow-lg shadow-emerald-900/20">
                            <i class="fas fa-child-reaching mr-1"></i> Add My First Mentee
                        </a>
                    </div>
                    <button onclick="closeWelcomeModal()" class="w-full mt-3 py-3 text-slate-400 text-[10px] font-black uppercase tracking-widest hover:text-slate-600 transition-colors">
                        I'll do it later — Go to Dashboard
                    </button>
                </div>
            </div>
        </div>

        {{-- Slide Indicators --}}
        <div class="flex justify-center gap-2 pb-6">
            <div class="slide-dot w-2 h-2 rounded-full bg-[#0B4D73] transition-all" data-dot="1"></div>
            <div class="slide-dot w-2 h-2 rounded-full bg-slate-200 transition-all" data-dot="2"></div>
            <div class="slide-dot w-2 h-2 rounded-full bg-slate-200 transition-all" data-dot="3"></div>
        </div>
        
        <script>
            function showSlide(num) {
                document.querySelectorAll('.onboarding-slide').forEach(s => s.classList.add('hidden'));
                document.querySelector(`.onboarding-slide[data-slide="${num}"]`).classList.remove('hidden');
                document.querySelectorAll('.slide-dot').forEach(d => {
                    d.classList.toggle('bg-[#0B4D73]', d.dataset.dot == num);
                    d.classList.toggle('bg-slate-200', d.dataset.dot != num);
                    d.style.width = d.dataset.dot == num ? '1.5rem' : '0.5rem';
                });
            }

            function closeWelcomeModal() {
                const modal = document.getElementById('welcomeModal');
                if (modal) {
                    modal.style.opacity = '0';
                    modal.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => modal.remove(), 300);
                }
            }
        </script>
    </div>
</div>
@endif

{{-- New Child Created Success Modal --}}
@if(session('new_child_created'))
<div id="childSuccessModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.7); backdrop-filter: blur(10px);">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-auth relative border border-white/20">
        <!-- Brand Background -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-800 p-8 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="w-20 h-20 mx-auto bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center mb-5 border border-white/20">
                    <i class="fas fa-child-reaching text-4xl text-emerald-200"></i>
                </div>
                <h2 class="text-2xl font-black mb-2 tracking-tight">{{ session('child_name') }} Added! 🎉</h2>
                <p class="text-emerald-100/80 text-sm font-medium">Account created successfully</p>
            </div>
        </div>

        <div class="p-8">
            <div class="space-y-6 mb-8">
                <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3">Temporary Login Details</p>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Email:</span>
                            <span class="font-black text-slate-900">{{ session('child_email') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-medium">Password:</span>
                            <span class="font-black text-slate-900 px-2 py-0.5 bg-white rounded-md border border-emerald-200">{{ session('child_password') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 bg-amber-50 rounded-2xl border border-amber-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fas fa-envelope-open-text text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-amber-800 uppercase tracking-wider mb-1">Important Next Step</p>
                        <p class="text-[11px] text-amber-700/80 font-medium leading-relaxed">
                            A confirmation link has been sent to <strong>{{ session('child_email') }}</strong>. Please ask {{ session('child_name') }} to check their <strong>Inbox and Spam folder</strong> to verify their account and set their own private password.
                        </p>
                    </div>
                </div>
            </div>

            <button onclick="closeChildSuccessModal()" class="w-full py-4 bg-[#0B4D73] text-white font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-900 transition-all shadow-lg active:scale-95">
                Got it, Thanks!
            </button>
        </div>
    </div>
</div>

<script>
    function closeChildSuccessModal() {
        const modal = document.getElementById('childSuccessModal');
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.3s ease';
            setTimeout(() => modal.remove(), 300);
        }
    }
</script>
@endif
@endsection

