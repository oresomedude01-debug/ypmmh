@extends('layouts.dashboard')

@section('title', 'Coming Soon')

@section('content')
    <div class="h-[70vh] flex flex-col items-center justify-center text-center space-y-6">
        <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center animate-pulse">
            <i class="fas fa-tools text-5xl text-[#0B4D73]"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-4xl font-bold text-slate-900">Feature Coming Soon</h1>
            <p class="text-slate-600 max-w-md mx-auto">
                We're currently working hard to bring this feature to life. Stay tuned for updates!
            </p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.dashboard') }}"
                class="px-8 py-3 bg-[#0B4D73] text-white font-bold rounded-xl hover:bg-[#093e5d] transition-all shadow-lg shadow-blue-900/10">
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection