@extends('layouts.dashboard')

@section('title', 'The Halaqah Circle')

@section('styles')
    <style>
        .circle-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-tap-highlight-color: transparent;
        }

        .circle-card:active {
            transform: scale(0.97);
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

            <div
                class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight mb-2">The Halaqah Circle</h1>
                    <p class="text-blue-200/70 text-sm font-medium leading-tight max-w-sm ml-auto mr-auto md:ml-0 md:mr-0">
                        Connect with your brothers and sisters. Share life, progress, and wisdom.
                    </p>
                </div>
                <div
                    class="w-16 h-16 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shadow-xl shrink-0">
                    🕌
                </div>
            </div>
        </div>

        <!-- Circles List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users text-[#0B4D73]"></i>
                    Your Circles
                </h2>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    {{ $programs->count() }} Active
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($programs as $program)
                    <a href="{{ route('child.communities.show', $program->id) }}"
                        class="circle-card bg-white rounded-[2rem] p-5 shadow-sm border border-slate-100 flex items-center gap-5 active:bg-slate-50 transition-colors">

                        <!-- Mini Avatar/Icon -->
                        <div
                            class="w-16 h-16 rounded-2xl bg-[#0B4D73] text-white flex items-center justify-center text-2xl shrink-0 shadow-lg shadow-blue-900/10">
                            <i class="fas fa-comments"></i>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-black text-slate-900 truncate mb-0.5">{{ $program->name }}</h3>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div
                                        class="w-5 h-5 rounded-md bg-slate-100 flex items-center justify-center text-[8px] font-black text-slate-400 overflow-hidden border border-slate-200">
                                        @if($program->mentor && $program->mentor->profile_picture)
                                            <img src="{{ asset('storage/' . $program->mentor->profile_picture) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($program->mentor->first_name ?? 'M', 0, 1) }}
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-500 font-bold truncate">
                                        {{ $program->mentor->full_name ?? 'Expert' }}
                                    </p>
                                </div>
                                <span class="text-[8px] font-black text-emerald-500 uppercase tracking-tighter">Active
                                    Now</span>
                                @if($program->unread_messages_count > 0)
                                    <span
                                        class="px-2 py-0.5 bg-red-500 text-white text-[8px] font-black rounded-full animate-pulse">
                                        {{ $program->unread_messages_count }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="text-[#0B4D73]">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                @empty
                    <div
                        class="bg-slate-50/50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200 col-span-2">
                        <i class="fas fa-ghost text-3xl text-slate-200 mb-3"></i>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-1">Silence in the Circle</h3>
                        <p class="text-xs text-slate-500">Assign to a program to join a community.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Honor Code -->
        <div class="bg-white border border-slate-100 rounded-[2rem] p-6 flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Hall of Honor</h4>
                <p class="text-[11px] font-medium text-slate-500 leading-tight">Be kind, respectful, and helpful. Every
                    voice counts here!</p>
            </div>
        </div>
    </div>
@endsection