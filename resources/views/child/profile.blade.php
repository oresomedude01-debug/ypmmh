@extends('layouts.dashboard')

@section('title', 'My Data & Profile')

@section('styles')
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }
        .data-card {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 1.5rem;
            padding: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6 animate-fade-in pb-20 md:pb-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-2 mb-2">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Profile Data</h1>
                <p class="text-sm text-slate-500 font-medium">View all your personal information and journey progress.</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-id-card"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Biodata -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User card -->
                <div class="hero-gradient rounded-[2rem] p-6 text-white text-center relative overflow-hidden shadow-xl shadow-blue-900/10">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="w-24 h-24 mx-auto rounded-full border-4 border-white/20 shadow-lg mb-4 overflow-hidden bg-white/10 flex items-center justify-center text-4xl font-black">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($child->first_name, 0, 1) }}
                            @endif
                        </div>
                        <h2 class="text-xl font-black tracking-tight mb-1">{{ $child->full_name }}</h2>
                        <div class="flex items-center justify-center gap-2">
                            <p class="text-xs font-bold text-blue-200 uppercase tracking-widest">{{ $child->unique_number }}</p>
                            @if($child->hasPremiumAccess())
                                <span class="bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md shadow flex items-center gap-1">
                                    <i class="fas fa-crown"></i> Premium
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bio Details Box -->
                <div class="data-card">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-user text-[#0B4D73]"></i> Personal Details
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Email Address</p>
                            <p class="text-sm font-bold text-slate-800">{{ $child->email }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Age</p>
                                <p class="text-sm font-bold text-slate-800">{{ $child->age ?? 'N/A' }} yrs</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Gender</p>
                                <p class="text-sm font-bold text-slate-800 capitalize">{{ $child->gender ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Date of Birth</p>
                            <p class="text-sm font-bold text-slate-800">{{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        @if($child->parent)
                        <div class="pt-3 border-t border-slate-100">
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mb-0.5">Parent / Guardian</p>
                            <p class="text-sm font-bold text-[#0B4D73]">{{ $child->parent->full_name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Stats & Programs -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="data-card !p-4 group hover:shadow-lg transition-all text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-lg mb-2">
                            <i class="fas fa-fire"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">{{ $stats['streak'] }}</h4>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Day Streak</p>
                    </div>
                    <div class="data-card !p-4 group hover:shadow-lg transition-all text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-lg mb-2">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">{{ $stats['xp'] }}</h4>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Total XP</p>
                    </div>
                    <div class="data-card !p-4 group hover:shadow-lg transition-all text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-2">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">Lvl {{ $stats['level'] }}</h4>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest truncate">{{ $stats['rank'] }}</p>
                    </div>
                    <div class="data-card !p-4 group hover:shadow-lg transition-all text-center">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900">{{ $stats['total_lessons'] }}</h4>
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Lessons Done</p>
                    </div>
                </div>

                <!-- Programs Summary -->
                <div class="data-card">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fas fa-route text-[#0B4D73]"></i> Journey History
                    </h3>

                    <!-- Ongoing -->
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-slate-500 mb-3 border-b border-slate-100 pb-2">Ongoing Programmes ({{ $ongoingPrograms->count() }})</h4>
                        <div class="space-y-3">
                            @forelse($ongoingPrograms as $p)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm border border-slate-200 flex items-center justify-center text-[#0B4D73]">
                                            <i class="fas {{ $p->type === 'rolling' ? 'fa-sync-alt' : ($p->type === 'journey' ? 'fa-route' : 'fa-building') }}"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $p->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $p->type }} track</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-[#0B4D73]">{{ $p->progress_percentage }}%</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs font-medium text-slate-400 py-2">No ongoing programmes right now.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Completed -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 mb-3 border-b border-slate-100 pb-2">Completed Programmes ({{ $completedPrograms->count() }})</h4>
                        <div class="space-y-3">
                            @forelse($completedPrograms as $p)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 border border-emerald-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm">
                                            <i class="fas fa-award"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $p->name }}</p>
                                            <p class="text-[10px] text-emerald-600 font-bold uppercase">Graduated</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs font-medium text-slate-400 py-2">No completed programmes yet. Keep going!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="text-center pt-4">
                    <a href="{{ route('child.achievements') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors font-bold text-xs uppercase tracking-widest shadow-sm border border-slate-200 gap-2">
                        View Detailed Achievements <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>

    </div>
@endsection
