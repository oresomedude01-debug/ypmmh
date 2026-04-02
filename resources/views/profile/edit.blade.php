@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('styles')
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0B4D73 0%, #1e40af 50%, #1e3a8a 100%);
        }

        .profile-card {
            transition: all 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in pb-20 md:pb-10">

        <!-- Premium Header -->
        <div class="hero-gradient rounded-[2.5rem] p-6 md:p-10 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <!-- Profile Avatar Preview -->
                <div class="relative">
                    <div class="absolute -inset-1 bg-white/20 rounded-full blur opacity-50"></div>
                    <div class="relative w-24 h-24 rounded-full border-4 border-white/30 overflow-hidden shadow-2xl">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-white/10 flex items-center justify-center text-2xl font-black">
                                {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight mb-1">{{ auth()->user()->full_name }}</h1>
                    <p class="text-blue-200/70 text-xs font-black uppercase tracking-[0.2em]">
                        {{ auth()->user()->roles->first()->name ?? 'Member' }} Account</p>
                </div>
            </div>
        </div>

        <!-- Settings Groups -->
        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-sm border border-slate-100/80">
                <div class="flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 rounded-xl bg-blue-50 text-[#0B4D73] flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-900">Personal Info</h2>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Security Section -->
            <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-sm border border-slate-100/80">
                <div class="flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-900">Security</h2>
                </div>
                @include('profile.partials.update-password-form')
            </div>

            @if(auth()->user()->hasRole('admin'))
                <!-- Advanced Controls -->
                <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-sm border border-red-50 text-red-900">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h2 class="text-lg font-black">Advanced Settings</h2>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            @endif
        </div>
    </div>
@endsection