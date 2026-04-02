@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
    <div class="w-full max-w-md px-4">
        <div class="p-8 md:p-12 rounded-[2.5rem] glass animate-auth">
            <!-- Brand Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-500 to-amber-400 text-white shadow-2xl mb-8">
                    <i class="fas fa-key text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-2">Reset Password</h1>
                <p class="text-[var(--text-secondary)] font-medium">Create a new password</p>
            </div>

            <form action="{{ route('password.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-input" placeholder=" " value="{{ old('email', $request->email) }}" required autofocus>
                    <label for="email" class="form-label">Email Address</label>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-input" placeholder=" " required>
                    <label for="password" class="form-label">New Password</label>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder=" " required>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-2 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-[var(--primary-500)] to-purple-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 mt-8">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection
