@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md px-4 sm:px-6">
        <div class="glass p-8 sm:p-12 rounded-[2.5rem] shadow-2xl relative animate-auth">
            <!-- Logo link -->
            <div class="mb-10 text-center">
                <a href="/" class="inline-block group transition-transform hover:scale-105">
                    <div class="w-16 h-16 bg-white p-3 rounded-2xl shadow-lg shadow-blue-900/10 mx-auto mb-4">
                        <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-full h-full object-contain">
                    </div>
                </a>
                <h2 class="text-3xl font-black text-slate-900">Welcome Back</h2>
                <p class="text-slate-500 mt-2 text-sm font-medium">Continue your path to excellence</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus class="form-input"
                        placeholder=" ">
                    <label for="email" class="form-label">
                        <i class="far fa-envelope mr-2 opacity-50"></i> Email Address
                    </label>
                    @error('email')
                        <p class="mt-2 text-[10px] text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input id="password" type="password" name="password" required class="form-input" placeholder=" ">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock mr-2 opacity-50"></i> Password
                    </label>
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-4 top-4 text-slate-400 hover:text-primary transition-colors">
                        <i class="fas fa-eye" id="passIcon"></i>
                    </button>
                    @error('password')
                        <p class="mt-2 text-[10px] text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-xs font-bold uppercase tracking-tighter">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-slate-300 text-primary shadow-sm focus:ring-primary/20 cursor-pointer w-4 h-4 transition-all"
                            name="remember">
                        <span class="ml-2 text-slate-500 group-hover:text-primary transition-colors">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-primary hover:text-cyan-600 transition-colors">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full py-4 rounded-2xl text-white font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 bg-gradient-to-r from-[#0B4D73] to-cyan-700">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary font-black hover:underline ml-1">Join YPMMH</a>
                </p>
            </div>

            <!-- Home Link -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="/"
                    class="text-xs text-slate-400 font-bold uppercase tracking-widest hover:text-primary transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left text-[10px]"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('passIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection