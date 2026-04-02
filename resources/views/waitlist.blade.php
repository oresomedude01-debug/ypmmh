@extends('layouts.public')

@section('title', 'Join Our Waitlist - YPMMH')

@section('content')
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden py-20 px-4">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-cyan-50"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-100/30 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>

        <!-- Container -->
        <div class="relative z-10 w-full max-w-lg">
            <div class="glass p-8 md:p-10 rounded-3xl shadow-2xl border border-white/50 backdrop-blur-xl">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#0B4D73] to-cyan-600 text-white mb-6 shadow-lg shadow-blue-900/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        <i class="fas fa-hourglass-half text-2xl animate-pulse"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 mb-2">Join the Waitlist</h1>
                    <p class="text-slate-500 text-sm font-medium">Be the first to know when we launch new programs and
                        features.</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3 animate-fade-in-up">
                        <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-green-800 text-sm">Success!</h4>
                            <p class="text-green-600 text-xs mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('waitlist.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Name Field -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-widest pl-1">Full
                            Name</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-user text-slate-400 group-focus-within:text-[#0B4D73] transition-colors"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#0B4D73]/20 focus:border-[#0B4D73] outline-none transition-all placeholder:text-slate-400"
                                placeholder="e.g. Amina Yusuf">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs pl-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-widest pl-1">Email
                            Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-envelope text-slate-400 group-focus-within:text-[#0B4D73] transition-colors"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#0B4D73]/20 focus:border-[#0B4D73] outline-none transition-all placeholder:text-slate-400"
                                placeholder="name@example.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs pl-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-1.5">
                        <label for="phone"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-widest pl-1">Phone Number <span
                                class="text-slate-400 font-normal lowercase">(optional)</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-phone text-slate-400 group-focus-within:text-[#0B4D73] transition-colors"></i>
                            </div>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#0B4D73]/20 focus:border-[#0B4D73] outline-none transition-all placeholder:text-slate-400"
                                placeholder="+234 800 000 0000">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs pl-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-4 px-6 rounded-xl bg-[#0B4D73] hover:bg-[#094263] text-white font-bold text-sm uppercase tracking-widest shadow-lg shadow-[#0B4D73]/20 hover:shadow-[#0B4D73]/40 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 group">
                        <span>Join Now</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>

                    <p class="text-center text-xs text-slate-400 font-medium mt-4">
                        We respect your inbox. No spam, ever.
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection