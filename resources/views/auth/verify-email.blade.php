@extends('layouts.auth')
@section('title', 'Verify Email')

@section('content')
    <div class="w-full max-w-sm px-4">
        <div class="p-6 rounded-2xl glass animate-auth">
            <!-- Brand Section -->
            <div class="text-center mb-6">
                @if (session('account_disabled'))
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-500 to-orange-400 text-white shadow-xl mb-4">
                        <i class="fas fa-ban text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight mb-1 text-red-600">Account Suspended</h1>
                    <p class="text-sm text-[var(--text-secondary)]">Verification Required</p>
                @else
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-500 to-blue-400 text-white shadow-xl mb-4">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight mb-1">Verify Email</h1>
                    <p class="text-sm text-[var(--text-secondary)]">Check your inbox</p>
                @endif
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-3 mb-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-xs">A new verification link has been sent.</p>
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 mb-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-xs">{{ session('error') }}</p>
                </div>
            @endif

            <div class="text-[var(--text-secondary)] text-center mb-6 space-y-2 text-sm">
                <p>Verify your email address by clicking the link we sent.</p>
                <p class="font-bold text-slate-700 text-xs">
                    <i class="fas fa-exclamation-circle text-amber-500 mr-1"></i>
                    Check SPAM/JUNK folder if not in inbox.
                </p>
            </div>

            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-[var(--primary-500)] to-purple-600 text-white font-bold rounded-xl hover:shadow-lg transition-all text-sm">
                    Resend Verification Email
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full py-2 px-4 text-[var(--primary-500)] font-semibold border border-[var(--primary-500)] rounded-xl hover:bg-[var(--primary-500)] hover:text-white transition-all text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection