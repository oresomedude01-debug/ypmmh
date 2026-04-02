@extends('layouts.auth')
@section('title', 'Confirm Password')

@section('content')
    <div class="w-full max-w-md px-4">
        <div class="p-8 md:p-12 rounded-[2.5rem] glass animate-auth">
            <!-- Brand Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-tr from-red-500 to-red-400 text-white shadow-2xl mb-8">
                    <i class="fas fa-lock text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-2">Confirm Password</h1>
                <p class="text-[var(--text-secondary)] font-medium">Please confirm your password</p>
            </div>

            <p class="text-[var(--text-secondary)] text-center mb-8 text-sm">
                For your security, please confirm your password before continuing.
            </p>

            <form action="{{ route('password.confirm') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Password Input -->
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-input" placeholder=" " required autofocus>
                    <label for="password" class="form-label">Password</label>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2 font-semibold"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-[var(--primary-500)] to-purple-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 mt-8">
                    Confirm
                </button>
            </form>
        </div>
    </div>
@endsection
