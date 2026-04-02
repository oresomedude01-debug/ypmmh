@extends('layouts.dashboard')

@section('title', 'My Journey')

@section('styles')
    <style>
        .gamified-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            -webkit-tap-highlight-color: transparent;
        }

        .gamified-card:active {
            transform: scale(0.98);
        }

        .streak-fire {
            animation: flicker 2s infinite ease-in-out;
            filter: drop-shadow(0 0 5px rgba(249, 115, 22, 0.4));
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.15); }
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }

        .xp-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Native App Feel Adjustments */
        @media (max-width: 768px) {
            .mobile-compact-padding {
                padding: 1rem !important;
            }
            .main-content main {
                padding: 1rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-5 animate-fade-in pb-20 md:pb-10">

        <!-- App-Style Compact Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <!-- Top Info Bar -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-8">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl blur opacity-75 animate-pulse"></div>
                            <div class="relative w-16 h-16 rounded-2xl overflow-hidden border-2 border-white/30 shadow-2xl">
                                @if($child->profile_picture)
                                    <img src="{{ asset('storage/' . $child->profile_picture) }}" alt="{{ $child->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xl font-black text-[#0B4D73]">
                                        {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-200/70 mb-0.5">Welcome back,</p>
                            <h1 class="text-xl md:text-2xl font-black tracking-tight">{{ $child->first_name }}! 👋</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <div class="xp-pill px-4 py-2 rounded-2xl flex items-center gap-2 shadow-inner">
                            <i class="fas fa-fire text-orange-400 streak-fire text-sm"></i>
                            <span class="text-sm font-black">{{ $child->streak }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid - App Style -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="xp-pill p-4 rounded-3xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:scale-125 transition-transform">
                            <i class="fas fa-bolt text-4xl"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-100/60 mb-1">Energy Points</p>
                        <div class="flex items-end gap-1">
                            <span class="text-2xl font-black">{{ $child->xp_points }}</span>
                            <span class="text-[10px] font-bold text-blue-200 mb-1.5 uppercase">XP</span>
                        </div>
                        <!-- Progress mini bar -->
                        <div class="mt-3 h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full shadow-[0_0_10px_rgba(250,204,21,0.5)]" style="width: {{ $child->xp_points % 100 }}%"></div>
                        </div>
                    </div>

                    <div class="xp-pill p-4 rounded-3xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:scale-125 transition-transform">
                            <i class="fas fa-crown text-4xl"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-100/60 mb-1">Current Rank</p>
                        <div class="flex items-end gap-1">
                            <span class="text-xl font-black truncate max-w-full">{{ $child->rank }}</span>
                        </div>
                        <p class="mt-3 text-[9px] font-black uppercase tracking-wider text-yellow-400">Level {{ floor($child->xp_points / 100) + 1 }} reached</p>
                    </div>
                </div>
            </div>
        </div>

        @if(!auth()->user()->hasPremiumAccess())
        <!-- Premium Reminder Banner -->
        <div class="px-2 mt-4">
            <div class="bg-gradient-to-r from-yellow-500 to-amber-600 rounded-[2rem] p-6 text-white shadow-lg shadow-yellow-500/30 relative overflow-hidden">
                <i class="fas fa-crown absolute opacity-10 -right-4 -bottom-4 text-[100px]"></i>
                <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black mb-1 flex items-center gap-2">
                            <i class="fas fa-lock"></i> Premium Access Required
                        </h3>
                        <p class="text-sm font-medium text-yellow-100">Unlock your Core Path (Rolling) programmes to continue your journey seamlessly.</p>
                    </div>
                    @if(\Carbon\Carbon::parse(auth()->user()->date_of_birth)->age >= 16)
                        <a href="{{ route('premium.subscribe') }}" class="px-6 py-3 bg-white text-yellow-600 rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg active:scale-95 transition-all whitespace-nowrap">
                            Subscribe Now
                        </a>
                    @else
                        <div class="px-6 py-3 bg-white/20 text-white rounded-xl font-black uppercase tracking-widest text-[10px] whitespace-nowrap text-center">
                            Ask Parent to Subscribe
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Main Journey Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-map-marked-alt text-[#0B4D73]"></i>
                    Active Quests
                </h2>
                <a href="{{ route('child.programs.index') }}" class="text-[10px] font-black text-[#0B4D73] uppercase tracking-widest hover:underline">
                    All Programmes
                </a>
            </div>

            <div class="space-y-4">
                @forelse($programs as $program)
                    @php
                        $isRolling = $program->type === 'rolling';
                        $isLocked = $isRolling && !auth()->user()->hasPremiumAccess();
                    @endphp
                    <div class="gamified-card {{ $isLocked ? 'bg-slate-800 text-slate-300 opacity-95 shadow-xl shadow-slate-900/30' : ($isRolling ? 'bg-gradient-to-br from-[#0B4D73] to-[#073652] text-white shadow-xl shadow-blue-900/30' : 'bg-white text-slate-900 shadow-sm border-slate-100/80') }} rounded-[2rem] p-5 border relative group overflow-hidden active:scale-95 transition-all">
                        
                        <!-- Core / Important Indicator -->
                        @if($isRolling)
                            <div class="absolute -right-12 -top-12 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="absolute top-0 right-0 px-4 py-1.5 bg-yellow-400 rounded-bl-2xl text-[9px] font-black text-yellow-900 uppercase tracking-widest shadow-lg z-10 flex items-center gap-1">
                                <i class="fas fa-star"></i> Core Path
                            </div>
                        @elseif($program->progress_percentage >= 100)
                            <div class="absolute -right-8 -top-8 w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center pointer-events-none">
                                <i class="fas fa-check text-emerald-500 translate-x-[-10px] translate-y-[10px] text-xl"></i>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                            <!-- Icon / Avatar -->
                            <div class="shrink-0">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center text-xl sm:text-2xl shadow-sm group-hover:scale-105 transition-transform {{ $isRolling ? 'bg-white/10 border-white/20 text-yellow-400' : 'bg-gradient-to-br from-blue-50 to-slate-50 border-blue-100/50 text-[#0B4D73]' }}">
                                    <i class="fas {{ $program->type === 'rolling' ? 'fa-sync-alt' : ($program->type === 'journey' ? 'fa-route' : 'fa-building') }}"></i>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-1 mt-[2px]">
                                    <h3 class="text-base font-black truncate {{ $isRolling ? 'text-white' : 'text-slate-900' }}">{{ $program->name }}</h3>
                                    @if($program->type === 'offline')
                                        <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[8px] font-black uppercase rounded-md">Physical</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-1">
                                    <div class="flex-1 max-w-[150px]">
                                        <div class="h-1.5 w-full rounded-full overflow-hidden {{ $isRolling ? 'bg-white/20' : 'bg-slate-100' }}">
                                            <div class="h-full rounded-full {{ $isRolling ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-gradient-to-r from-[#0B4D73] to-blue-500' }}" style="width: {{ $program->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black capitalize {{ $isRolling ? 'text-blue-100' : 'text-slate-400' }}">{{ $program->progress_percentage }}% Finish</span>
                                </div>
                            </div>

                                <!-- Action -->
                                <div class="flex flex-col justify-center">
                                    @if($program->type !== 'offline')
                                        @if($isLocked)
                                            <a href="{{ \Carbon\Carbon::parse(auth()->user()->date_of_birth)->age >= 16 ? route('premium.subscribe') : '#' }}" 
                                               onclick="{{ \Carbon\Carbon::parse(auth()->user()->date_of_birth)->age < 16 ? 'alert(\'Please ask your parent to subscribe to Premium to unlock this core course.\'); return false;' : '' }}"
                                               class="w-12 h-12 flex items-center justify-center rounded-2xl shadow-lg active:scale-90 transition-all bg-white/10 text-white hover:bg-white/20 border border-white/10 shadow-black/20 text-lg">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('child.programs.show', $program->id) }}" 
                                               class="w-12 h-12 flex items-center justify-center rounded-2xl shadow-lg active:scale-90 transition-all {{ $isRolling ? 'bg-white/20 text-white hover:bg-white/30 border border-white/10 shadow-black/20 text-lg' : 'bg-[#0B4D73] text-white shadow-blue-900/20' }}">
                                                <i class="fas {{ $isRolling ? 'fa-play' : 'fa-chevron-right' }}"></i>
                                            </a>
                                        @endif
                                    @else
                                    <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 border border-emerald-200">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-50/50 rounded-[2rem] p-12 text-center border-2 border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="fas fa-compass text-2xl text-slate-300"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-1">No Paths Found</h3>
                        <p class="text-xs text-slate-500">Your mentor will assign your first quest soon!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Social Activity Hub - Quick Access -->
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('child.communities.index') }}" class="gamified-card bg-gradient-to-br from-indigo-50 to-white p-5 rounded-[2.5rem] border border-indigo-100 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-comments"></i>
                </div>
                <span class="text-xs font-black text-indigo-900 uppercase tracking-widest">Village Chat</span>
            </a>
            
            <a href="{{ route('child.achievements') }}" class="gamified-card bg-gradient-to-br from-amber-50 to-white p-5 rounded-[2.5rem] border border-amber-100 shadow-sm flex flex-col items-center text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl shadow-lg shadow-amber-500/20">
                    <i class="fas fa-trophy"></i>
                </div>
                <span class="text-xs font-black text-amber-900 uppercase tracking-widest">The Vault</span>
            </a>
        </div>

        <!-- Mini Leaderboard -->
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100/80">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    Hall of Fame
                </h3>
                <span class="text-[10px] font-black text-slate-400">Weekly Top</span>
            </div>
            
            <div class="space-y-4">
                @foreach($leaderboard->take(3) as $index => $player)
                    <div class="flex items-center justify-between p-3 rounded-2xl {{ $player->id === $child->id ? 'bg-blue-50 border border-blue-100 shadow-sm' : '' }}">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                @if($index === 0) <i class="fas fa-crown absolute -top-3 -left-1 text-yellow-500 -rotate-12 z-10 text-xs"></i> @endif
                                <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden border border-slate-200">
                                    @if($player->profile_picture)
                                        <img src="{{ asset('storage/' . $player->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs font-black text-slate-400">{{ substr($player->first_name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <span class="absolute -bottom-2 -right-2 w-5 h-5 bg-white border border-slate-100 rounded-full flex items-center justify-center text-[8px] font-black text-slate-700 shadow-sm">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                            <span class="text-xs font-black text-slate-700">{{ $player->first_name }} {{ $player->id === $child->id ? '(You)' : '' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                            <span class="text-xs font-black text-[#0B4D73]">{{ $player->xp_points }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <a href="{{ route('child.achievements') }}" class="mt-6 flex items-center justify-center py-4 bg-slate-50 text-slate-400 rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-100 transition-colors">
                See Full Standing <i class="fas fa-chevron-right ml-2"></i>
            </a>
        </div>
    </div>
@endsection
