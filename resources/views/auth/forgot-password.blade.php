@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
    <div class="w-full max-w-md px-4">
        <div class="p-8 md:p-12 rounded-[2.5rem] glass animate-auth">
            <!-- Brand Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-500 to-amber-400 text-white shadow-2xl mb-8">
                    <i class="fas fa-key text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-2">Forgot Password</h1>
                <p class="text-[var(--text-secondary)] font-medium">Reset your password</p>
            </div>

            @if (session('status'))
                <div class="p-4 mb-6 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <p class="text-[var(--text-secondary)] text-center mb-8 text-sm">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
            </p>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-input" placeholder=" " value="{{ old('email') }}" required autofocus>
                    <label for="email" class="form-label">Email Address</label>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-[var(--primary-500)] to-purple-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 mt-8">
                    Email Password Reset Link
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-[var(--text-secondary)] text-sm mt-8">
                Remember your password?
                <a href="{{ route('login') }}" class="text-[var(--primary-500)] font-semibold hover:underline">Login here</a>
            </p>
        </div>
    </div>
@endsection
