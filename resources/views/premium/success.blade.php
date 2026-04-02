@extends('layouts.frontend')

@section('title', 'Subscription Successful')

@section('content')
<main class="pt-32 pb-24 relative overflow-hidden bg-emerald-50 min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0">
        <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-emerald-400/20 rounded-full blur-[120px] mix-blend-multiply"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-green-400/20 rounded-full blur-[120px] mix-blend-multiply"></div>
    </div>

    <div class="max-w-lg mx-auto px-4 sm:px-6 w-full z-10 text-center">
        <div class="w-32 h-32 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full mx-auto flex flex-col items-center justify-center text-white text-5xl mb-8 shadow-2xl shadow-emerald-500/40 relative overflow-hidden animate-bounce-slow">
            <div class="absolute inset-0 bg-[url('/img/noise.png')] opacity-20 mix-blend-overlay"></div>
            <i class="fas fa-check relative z-10"></i>
        </div>

        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Access Granted!</h1>
        <p class="text-slate-600 mb-10 text-lg leading-relaxed font-medium">
            Your premium subscription has been successfully activated. The Core Path is now fully unlocked.
        </p>

        <a href="{{ route('dashboard') }}" class="inline-block px-10 py-5 bg-[#0B4D73] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-900/30 hover:-translate-y-1 hover:shadow-2xl hover:bg-slate-900 transition-all">
            Return to Dashboard
        </a>
    </div>
</main>
@endsection
