@extends('layouts.dashboard')

@section('title', 'All Programmes')

@section('styles')
    <style>
        .gamified-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            -webkit-tap-highlight-color: transparent;
        }

        .gamified-card:active {
            transform: scale(0.98);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6 animate-fade-in pb-20 md:pb-10">

        <!-- App Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight mb-2">My Programmes</h1>
                    <p class="text-blue-200/70 text-sm font-medium leading-tight max-w-sm ml-auto mr-auto md:ml-0 md:mr-0">
                        A full list of every quest and programme currently assigned to you.
                    </p>
                </div>
                <div class="w-16 h-16 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shadow-xl shrink-0">
                    🗺️
                </div>
            </div>
        </div>

        <!-- Programmes List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-scroll text-[#0B4D73]"></i>
                    All Enrolled Paths
                </h2>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    {{ $programs->count() }} Total
                </span>
            </div>

            <div class="space-y-4">
                @forelse($programs as $program)
                    @php
                        $isRolling = $program->type === 'rolling';
                        $isLocked = $isRolling && !auth()->user()->hasPremiumAccess();
                    @endphp
                    <div class="gamified-card {{ $isLocked ? 'bg-slate-800 text-slate-300 opacity-95 shadow-xl shadow-slate-900/30' : ($isRolling ? 'bg-gradient-to-br from-[#0B4D73] to-[#073652] text-white shadow-xl shadow-blue-900/30' : 'bg-white text-slate-900 border-slate-100/80') }} rounded-[2rem] p-5 border relative group overflow-hidden active:bg-slate-50 transition-all">
                        
                        @if($isLocked)
                            <div class="absolute -right-12 -top-12 w-32 h-32 bg-slate-900/50 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="absolute top-0 right-0 px-4 py-1.5 bg-slate-600 rounded-bl-2xl text-[9px] font-black text-slate-200 uppercase tracking-widest shadow-lg z-10 flex items-center gap-1">
                                <i class="fas fa-lock"></i> Locked
                            </div>
                        @elseif($isRolling)
                            <div class="absolute -right-12 -top-12 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="absolute top-0 right-0 px-4 py-1.5 bg-yellow-400 rounded-bl-2xl text-[9px] font-black text-yellow-900 uppercase tracking-widest shadow-lg z-10 flex items-center gap-1">
                                <i class="fas fa-star"></i> Core Path
                            </div>
                        @elseif($program->pivot->status !== 'active')
                            <div class="absolute top-0 right-0 px-3 py-1 bg-slate-100 rounded-bl-xl text-[9px] font-black text-slate-400 uppercase tracking-widest z-10">
                                {{ ucfirst($program->pivot->status) }}
                            </div>
                        @elseif($program->progress_percentage >= 100)
                            <div class="absolute -right-8 -top-8 w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-emerald-500 translate-x-[-10px] translate-y-[10px] text-xl"></i>
                            </div>
                        @else
                            <div class="absolute top-0 right-0 px-3 py-1 bg-emerald-100 rounded-bl-xl text-[9px] font-black text-emerald-600 uppercase tracking-widest z-10">
                                Active
                            </div>
                        @endif

                        <div class="flex gap-5">
                            <!-- Icon / Avatar -->
                            <div class="shrink-0">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl shadow-sm group-hover:scale-105 transition-transform {{ $isLocked ? 'bg-slate-900/50 border-slate-700' : ($isRolling ? 'bg-white/10 border-white/20 text-yellow-400' : ($program->pivot->status === 'active' ? 'bg-gradient-to-br from-blue-50 to-slate-50 border-blue-100/50 text-[#0B4D73]' : 'bg-slate-50 border-slate-100 text-slate-400')) }}">
                                    <i class="fas {{ $isRolling ? 'fa-sync-alt' : ($program->type === 'journey' ? 'fa-route' : 'fa-building') }}"></i>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-base font-black text-slate-900 truncate">{{ $program->name }}</h3>
                                    @if($program->type === 'offline')
                                        <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[8px] font-black uppercase rounded-md">Physical</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 max-w-[150px]">
                                        <div class="h-1.5 w-full {{ $isRolling ? 'bg-white/20' : 'bg-slate-100' }} rounded-full overflow-hidden">
                                            <div class="h-full {{ $isRolling ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : ($program->pivot->status === 'active' ? 'bg-gradient-to-r from-[#0B4D73] to-blue-500' : 'bg-slate-400') }} rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-black {{ $isRolling ? 'text-blue-100' : 'text-slate-400' }} capitalize">{{ $program->progress_percentage }}% Finish</span>
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
                                            class="w-12 h-12 flex items-center justify-center rounded-2xl {{ $isRolling ? 'bg-white/20 text-white hover:bg-white/30 border border-white/10 shadow-black/20 text-lg' : ($program->pivot->status === 'active' ? 'bg-[#0B4D73] text-white shadow-lg shadow-blue-900/20' : 'bg-slate-200 text-slate-500') }} active:scale-90 transition-all cursor-pointer">
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
                        <p class="text-xs text-slate-500">You are not enrolled in any programmes at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
