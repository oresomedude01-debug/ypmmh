@extends('layouts.dashboard')

@section('title', $child->first_name . '\'s Progress')

@section('styles')
    <style>
        .report-card {
            transition: all 0.3s ease;
        }

        .report-card:hover {
            border-color: #0B4D73;
            background: #f8fbff;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in pb-10">
        <!-- Back to Overview -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('parent.dashboard') }}"
                class="inline-flex items-center gap-2 text-xs font-black text-[#0B4D73] uppercase tracking-widest hover:gap-3 transition-all">
                <i class="fas fa-arrow-left"></i> Family Overview
            </a>
            <a href="{{ route('parent.children.edit', $child) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#0B4D73] rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-100 transition-all">
                <i class="fas fa-pen"></i> Edit Profile
            </a>
        </div>

        <!-- Child Profile Card -->
        <div
            class="flex flex-col md:flex-row gap-8 items-center bg-white rounded-[2.5rem] p-8 md:p-10 shadow-xl border border-slate-100">
            <div class="relative shrink-0">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#0B4D73] to-blue-500 rounded-3xl blur opacity-25">
                </div>
                <div class="relative w-32 h-32 md:w-40 md:h-40 rounded-3xl overflow-hidden border-4 border-white shadow-xl">
                    @if($child->profile_picture)
                        <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-slate-50 flex items-center justify-center text-4xl font-black text-[#0B4D73]">
                            {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-3">
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">{{ $child->full_name }}</h1>
                    <span
                        class="px-3 py-1 bg-blue-50 text-[#0B4D73] text-[10px] font-black uppercase rounded-lg border border-blue-100">Grade
                        {{ $child->grade ?? 'Year 5' }}</span>
                </div>
                <p class="text-slate-500 font-medium mb-6 leading-relaxed max-w-xl">
                    Currently tracking {{ $child->enrollments->count() }} specialized learning paths.
                    {{ $child->first_name }} has shown remarkable consistency in community sessions.
                </p>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-8">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Rank Status</p>
                        <p class="text-xl font-black text-[#0B4D73] flex items-center gap-2">
                            <i class="fas fa-crown text-yellow-500 text-sm"></i>
                            {{ $child->rank }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total XP</p>
                        <p class="text-xl font-black text-slate-900">{{ $child->xp_points }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Streak</p>
                        <p class="text-xl font-black text-orange-500">{{ $child->streak }} Days</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Learning Milestones -->
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2 px-2">
                    <i class="fas fa-scroll text-[#0B4D73]"></i>
                    Learning Milestones
                </h2>

                <div class="space-y-4">
                    @foreach($child->enrollments as $enrollment)
                        @if(!$enrollment->program)
                            @continue
                        @endif
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-black text-slate-900 leading-tight mb-1">{{ $enrollment->program->name }}
                                    </h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                                        {{ $enrollment->program->type }} Path
                                    </p>
                                    @if($enrollment->program->type === 'offline')
                                        <a href="{{ route('parent.programs.pass', [$enrollment->program->id, $child->id]) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-tighter hover:bg-indigo-100 mt-2">
                                            <span>Print Entry Pass</span>
                                            <i class="fas fa-print text-[9px]"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @php
                                        $visibleCount = $enrollment->program->contents()->where('is_active', true)->count();
                                        $completed = $child->lessonProgress()
                                            ->whereIn('program_content_id', $enrollment->program->contents->pluck('id'))
                                            ->whereNotNull('completed_at')
                                            ->count();
                                        $percent = $visibleCount > 0 ? round(($completed / $visibleCount) * 100) : 0;
                                    @endphp
                                    <span class="text-lg font-black text-[#0B4D73]">{{ $percent }}%</span>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Mastery</p>
                                </div>
                            </div>
                            <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100/50">
                                <div class="h-full bg-gradient-to-r from-[#0B4D73] to-blue-600 rounded-full transition-all duration-1000"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mentor Observations -->
                <div class="pt-6">
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-2 px-2 mb-6">
                        <i class="fas fa-quote-left text-[#0B4D73]"></i>
                        Mentor Observations
                    </h2>
                    <div class="space-y-4">
                        @forelse($child->observations as $obs)
                            @if(!$obs->mentor)
                                @continue
                            @endif
                            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative">
                                <i class="fas fa-quote-right absolute top-6 right-8 text-slate-50 text-5xl"></i>
                                <div class="relative z-10">
                                    <p class="text-slate-600 font-medium leading-relaxed italic mb-6">"{{ $obs->content }}"</p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-[#0B4D73] text-white flex items-center justify-center text-[10px] font-black">
                                            {{ substr($obs->mentor->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-900 leading-none mb-1">Mentor
                                                {{ $obs->mentor->full_name }}
                                            </p>
                                            <p class="text-[9px] font-black text-[#0B4D73] uppercase tracking-widest">
                                                {{ $obs->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-[2rem] p-12 text-center border-2 border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Awaiting first observation
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar: Reports & Medals -->
            <div class="space-y-8">
                <!-- Monthly Reports -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl">
                    <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-file-pdf text-[#0B4D73]"></i>
                        Monthly Reports
                    </h3>
                    <div class="space-y-3">
                        @forelse($reports as $report)
                            <div
                                class="report-card flex items-center justify-between p-4 rounded-2xl border border-slate-100 group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B4D73] flex items-center justify-center">
                                        <i class="fas fa-file-excel text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            {{ \Carbon\Carbon::create()->month($report->report_month)->format('F') }}
                                            {{ $report->report_year }}
                                        </p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Progress Review
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('parent.reports.download', $child->id) }}?month={{ $report->report_month }}&year={{ $report->report_year }}"
                                    class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-[#0B4D73] hover:bg-[#0B4D73] hover:text-white transition-all">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No reports available
                                    yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Awarded Medals (Small Preview) -->
                <div class="bg-gradient-to-br from-[#0B4D73] to-[#04334d] rounded-[2.5rem] p-8 text-white shadow-xl">
                    <h3 class="text-lg font-black mb-6 flex items-center gap-2">
                        <i class="fas fa-medal text-yellow-400"></i>
                        Awarded Medals
                    </h3>
                    <div class="grid grid-cols-4 gap-4">
                        @php
                            $allMedalDefinitions = [
                                'first_step' => ['name' => 'First Step', 'description' => 'Completed 3 lessons to start your path!', 'icon' => 'fa-shoe-prints', 'color' => 'bg-emerald-500'],
                                'chatterbox' => ['name' => 'Circle Speaker', 'description' => 'Contributed 50 messages to The Halaqah.', 'icon' => 'fa-comment-dots', 'color' => 'bg-cyan-500'],
                                'heart_of_gold' => ['name' => 'Heart of Gold', 'description' => 'Received 50 "Love" reactions from peers.', 'icon' => 'fa-heart', 'color' => 'bg-rose-500'],
                                'social_hero' => ['name' => 'Social Hero', 'description' => 'Gave 100 reactions to support others!', 'icon' => 'fa-hands-helping', 'color' => 'bg-teal-500'],
                                'knowledge_seeker' => ['name' => 'Knowledge Seeker', 'description' => 'Completed 20 lessons.', 'icon' => 'fa-book-open', 'color' => 'bg-blue-500'],
                                'the_popular' => ['name' => 'The Popular', 'description' => 'Received 200 "Like" reactions!', 'icon' => 'fa-thumbs-up', 'color' => 'bg-blue-600'],
                                'on_fire' => ['name' => 'Consistent Explorer', 'description' => 'Maintained a 7-day streak!', 'icon' => 'fa-fire', 'color' => 'bg-orange-500'],
                                'program_graduate' => ['name' => 'Program Graduate', 'description' => 'Successfully finished your first entire program!', 'icon' => 'fa-graduation-cap', 'color' => 'bg-indigo-600'],
                                'trailblazer' => ['name' => 'Trailblazer', 'description' => 'Completed 50 lessons on your journey.', 'icon' => 'fa-mountain', 'color' => 'bg-indigo-500'],
                                'committed' => ['name' => 'The Dedicated', 'description' => 'Maintained a 21-day activity streak!', 'icon' => 'fa-calendar-check', 'color' => 'bg-pink-500'],
                                'voice_of_wisdom' => ['name' => 'Halaqah Legend', 'description' => 'Contributed 200 messages in your community.', 'icon' => 'fa-crown', 'color' => 'bg-amber-600'],
                                'master_of_one' => ['name' => 'Master of One', 'description' => 'Completely finished 3 different programs!', 'icon' => 'fa-award', 'color' => 'bg-rose-600'],
                                'rising_star' => ['name' => 'Rising Star', 'description' => 'Reached Level 10.', 'icon' => 'fa-star', 'color' => 'bg-yellow-500'],
                                'consistent_scholar' => ['name' => 'Consistent Scholar', 'description' => 'Maintained a 50-day streak!', 'icon' => 'fa-bolt', 'color' => 'bg-yellow-600'],
                                'circle_pillar' => ['name' => 'Circle Pillar', 'description' => 'Sent 500 messages in The Halaqah.', 'icon' => 'fa-hamsa', 'color' => 'bg-purple-600'],
                                'journey_veteran' => ['name' => 'Journey Veteran', 'description' => 'Finished 5 entire programs!', 'icon' => 'fa-scroll', 'color' => 'bg-slate-700'],
                                'high_achiever' => ['name' => 'High Achiever', 'description' => 'Completed 100 lessons!', 'icon' => 'fa-medal', 'color' => 'bg-emerald-700'],
                                'elite_explorer' => ['name' => 'Guardian of Knowledge', 'description' => 'Reached high-tier Level 50!', 'icon' => 'fa-gem', 'color' => 'bg-blue-800'],
                            ];
                        @endphp

                        @foreach($child->achievements->take(8) as $achievement)
                            @php
                                $medal = $allMedalDefinitions[$achievement->achievement_id] ?? ['name' => 'Unknown Achievement', 'description' => 'A mysterious milestone reached!', 'icon' => 'fa-medal', 'color' => 'bg-slate-500'];
                            @endphp
                            <button
                                onclick="showMedalDetails('{{ $medal['name'] }}', '{{ $medal['description'] }}', '{{ $medal['icon'] }}', '{{ $medal['color'] }}')"
                                class="w-10 h-10 rounded-full {{ $medal['color'] }} flex items-center justify-center text-xs shadow-lg border border-white/20 hover:scale-110 active:scale-95 transition-all cursor-pointer"
                                title="Click to view details">
                                <i class="fas {{ $medal['icon'] }}"></i>
                            </button>
                        @endforeach
                        @if($child->achievements->count() > 8)
                            <div
                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-[10px] font-black border border-white/10">
                                +{{ $child->achievements->count() - 8 }}
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <p class="text-[10px] font-black text-blue-100/60 uppercase tracking-widest leading-relaxed">
                            {{ $child->first_name }}'s collection is growing! Click any medal to understand what it stands
                            for.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medal Detail Modal (Parent Portal) -->
    <div id="medalModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeMedalModal()"></div>
        <div class="relative bg-white rounded-[2.5rem] p-8 md:p-10 max-w-sm w-full shadow-2xl animate-scale-in text-center">
            <button onclick="closeMedalModal()"
                class="absolute top-6 right-6 text-slate-300 hover:text-slate-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div id="modalMedalIcon"
                class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl text-white shadow-xl mb-6">
                <i id="medalIcon" class="fas"></i>
            </div>
            <h2 id="modalMedalName" class="text-2xl font-black text-slate-900 mb-2"></h2>
            <div class="w-12 h-1 bg-[#0B4D73]/10 mx-auto mb-4 rounded-full"></div>
            <p id="modalMedalDesc" class="text-slate-500 font-medium leading-relaxed mb-8"></p>
            <button onclick="closeMedalModal()"
                class="w-full py-4 bg-[#0B4D73] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg hover:shadow-blue-900/20 transition-all active:scale-95">
                Got it
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        function showMedalDetails(name, desc, icon, color) {
            document.getElementById('modalMedalName').innerText = name;
            document.getElementById('modalMedalDesc').innerText = desc;
            document.getElementById('modalMedalIcon').className = `w-24 h-24 rounded-full mx-auto flex items-center justify-center text-4xl text-white shadow-xl mb-6 ${color}`;
            document.getElementById('medalIcon').className = `fas ${icon}`;
            document.getElementById('medalModal').classList.remove('hidden');

            confetti({
                particleCount: 80,
                spread: 60,
                origin: { y: 0.6 },
                colors: ['#0B4D73', '#FFD700', '#4F46E5'],
                zIndex: 11000
            });
        }

        function closeMedalModal() {
            document.getElementById('medalModal').classList.add('hidden');
        }
    </script>
@endsection