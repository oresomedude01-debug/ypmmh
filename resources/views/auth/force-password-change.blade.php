@extends('layouts.guest')

@section('title', 'Change Your Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background-color: var(--bg-primary);">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[70%] rounded-full blur-3xl opacity-20" style="background: linear-gradient(135deg, var(--primary-500), #8b5cf6);"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[60%] h-[60%] rounded-full blur-3xl opacity-20" style="background: linear-gradient(135deg, #0ea5e9, var(--primary-500));"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10 animate-fade-in glass p-10 rounded-3xl shadow-xl border" style="border-color: var(--border-color);">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(11, 77, 115, 0.1); color: var(--primary-500);">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black mb-2" style="color: var(--text-primary);">Update Password</h2>
            <p class="text-sm" style="color: var(--text-secondary);">
                For your security, please change your default password to something unique before continuing.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('password.change.update') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-bold mb-1" style="color: var(--text-primary);">New Password</label>
                    <input id="password" name="password" type="password" required 
                           class="w-full px-4 py-3 rounded-xl border focus:ring-2 outline-none transition-all"
                           style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary); --tw-ring-color: var(--primary-500);"
                           placeholder="Enter new password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold mb-1" style="color: var(--text-primary);">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="w-full px-4 py-3 rounded-xl border focus:ring-2 outline-none transition-all"
                           style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary); --tw-ring-color: var(--primary-500);"
                           placeholder="Confirm new password">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-xl text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02]"
                        style="background: linear-gradient(135deg, var(--primary-500), var(--primary-600));">
                    <i class="fas fa-save mr-2 my-auto"></i> Update Password & Continue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
