@extends('layouts.auth')
@section('title', 'Set Your Password')

@section('content')
    <div class="w-full max-w-md px-4">
        <div class="p-8 md:p-12 rounded-[2.5rem] glass animate-auth">
            <!-- Brand Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-2xl mb-8">
                    <i class="fas fa-shield-halved text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-2">Welcome to YPMMH!</h1>
                <p class="text-[var(--text-secondary)] font-medium">Set Your Secure Password</p>
            </div>

            <p class="text-[var(--text-secondary)] text-center mb-8 text-sm">
                Alhamdulillah! Your email has been verified successfully. 
                Please set your password to complete your registration.
            </p>

            <form action="{{ route('child.set-password.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Password Input -->
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-input" placeholder=" " required autofocus>
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

                <!-- Info Box -->
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-2xl mb-6">
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">Security Requirements</p>
                    <ul class="text-[11px] text-slate-600 space-y-1 font-medium">
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[8px]"></i> At least 8 characters</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[8px]"></i> Uppercase & lowercase letters</li>
                        <li class="flex items-center gap-2"><i class="fas fa-check text-[8px]"></i> At least one number</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-xl transition-all duration-300 mt-8">
                    Set Password & Continue
                </button>
            </form>

            <!-- Login Link -->
            <p class="text-center text-[var(--text-secondary)] text-sm mt-8">
                Already have a password?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Log in here</a>
            </p>
        </div>
    </div>
@endsection

