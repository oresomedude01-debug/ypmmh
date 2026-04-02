@extends('layouts.dashboard')

@section('title', 'My Achievements')

@section('styles')
    <style>
        .medal-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            -webkit-tap-highlight-color: transparent;
        }

        .medal-card:active {
            transform: scale(0.95);
        }

        .medal-unlocked {
            animation: pulse-glow 2s infinite alternate;
        }

        @keyframes pulse-glow {
            from { box-shadow: 0 0 10px rgba(11, 77, 115, 0.1); }
            to { box-shadow: 0 0 20px rgba(11, 77, 115, 0.3); }
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }

        .vault-pill {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6 animate-fade-in pb-20 md:pb-10">

        <!-- Native Style Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-8 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 rounded-3xl bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30 shadow-inner mb-4">
                    <i class="fas fa-trophy text-3xl text-yellow-400"></i>
                </div>
                <h1 class="text-2xl font-black tracking-tight mb-1">The Vault</h1>
                <p class="text-blue-200/70 text-[10px] font-black uppercase tracking-[0.2em] mb-6">Medals & Milestones</p>

                <div class="grid grid-cols-2 gap-3 w-full max-w-sm">
                    <div class="vault-pill p-3 rounded-2xl">
                        <p class="text-[8px] font-black uppercase text-blue-200/50 mb-0.5">Energy</p>
                        <p class="text-lg font-black">{{ $stats['xp'] }} XP</p>
                    </div>
                    <div class="vault-pill p-3 rounded-2xl">
                        <p class="text-[8px] font-black uppercase text-blue-200/50 mb-0.5">League</p>
                        <p class="text-lg font-black">{{ $stats['rank'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medals Grid -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-black text-slate-900">Hall of Fame</h2>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    {{ count(array_filter($medals, fn($m) => $m['unlocked'])) }} / {{ count($medals) }} Found
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($medals as $medal)
                    <div onclick="celebrateMedal('{{ $medal['name'] }}', '{{ $medal['description'] }}', '{{ $medal['icon'] }}', '{{ $medal['color'] }}', {{ $medal['unlocked'] ? 'true' : 'false' }})"
                        class="medal-card bg-white rounded-3xl p-5 border border-slate-100 flex flex-col items-center text-center relative overflow-hidden {{ $medal['unlocked'] ? 'medal-unlocked cursor-pointer shadow-sm active:bg-slate-50' : 'opacity-40 grayscale cursor-not-allowed' }}">

                        <div
                            class="w-12 h-12 rounded-2xl {{ $medal['color'] }} text-white flex items-center justify-center text-xl shadow-lg mb-3">
                            <i class="fas {{ $medal['icon'] }}"></i>
                        </div>

                        <h3 class="text-[10px] font-black text-slate-900 leading-tight mb-1">{{ $medal['name'] }}</h3>

                        @if($medal['unlocked'])
                            <div
                                class="mt-2 py-0.5 px-2 bg-emerald-50 text-emerald-500 rounded-md text-[8px] font-black uppercase tracking-wider">
                                Collected</div>
                        @else
                            <div
                                class="mt-2 py-0.5 px-2 bg-slate-50 text-slate-400 rounded-md text-[8px] font-black uppercase tracking-wider">
                                Locked</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Progression Box -->
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100/80 space-y-6">
            <div class="space-y-4">
                <div class="flex justify-between items-end">
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Adventure Level</h3>
                        <p class="text-lg font-black text-[#0B4D73]">Level {{ $stats['level'] + 1 }} Progress</p>
                    </div>
                    <p class="text-[10px] font-black text-slate-400">{{ $stats['xp'] % 100 }}/100 XP</p>
                </div>

                <div class="h-3 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100 p-0.5">
                    <div class="h-full bg-gradient-to-r from-[#0B4D73] to-blue-500 rounded-full shadow-[0_0_8px_rgba(11,77,115,0.2)]"
                        style="width: {{ $stats['xp'] % 100 }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Lessons Done</p>
                    <p class="text-xl font-black text-slate-900">{{ $stats['total_lessons'] }}</p>
                </div>
                <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100">
                    <p class="text-[8px] font-black text-orange-400 uppercase mb-1">Daily Streak</p>
                    <p class="text-xl font-black text-orange-600">{{ $stats['streak'] }} Days</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Celebration Modal (Simplified) -->
    <div id="medalModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeMedalModal()"></div>
        <div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl animate-scale-in text-center">
            <button onclick="closeMedalModal()" class="absolute top-5 right-5 text-slate-300 hover:text-slate-500">
                <i class="fas fa-times"></i>
            </button>
            <div id="modalMedalIcon"
                class="w-20 h-20 rounded-full mx-auto flex items-center justify-center text-4xl text-white shadow-xl mb-6">
                <i id="medalIcon" class="fas"></i>
            </div>
            <h2 id="modalMedalName" class="text-2xl font-black text-slate-900 mb-2"></h2>
            <p id="modalMedalDesc" class="text-slate-500 text-sm font-medium mb-6"></p>
            <button onclick="closeMedalModal()"
                class="w-full py-4 bg-[#0B4D73] text-white rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg hover:shadow-blue-900/20 transition-all">
                Close Vault
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        function celebrateMedal(name, desc, icon, color, unlocked) {
            if (!unlocked) return;

            document.getElementById('modalMedalName').innerText = name;
            document.getElementById('modalMedalDesc').innerText = desc;
            document.getElementById('modalMedalIcon').className = `w-20 h-20 rounded-full mx-auto flex items-center justify-center text-4xl text-white shadow-xl mb-6 ${color}`;
            document.getElementById('medalIcon').className = `fas ${icon}`;
            document.getElementById('medalModal').classList.remove('hidden');

            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                zIndex: 11000
            });
        }

        function closeMedalModal() {
            document.getElementById('medalModal').classList.add('hidden');
        }
    </script>
@endsection