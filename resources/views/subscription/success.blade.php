@extends('layouts.dashboard')

@section('title', 'Payment Successful')

@section('content')
    <div class="max-w-xl mx-auto py-12 text-center">
        <!-- Success Animation -->
        <div class="relative inline-block mb-8">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white shadow-2xl shadow-emerald-500/40 animate-bounce"
                style="animation-duration: 2s;">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <div
                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-white animate-pulse">
                <i class="fas fa-star text-sm"></i>
            </div>
        </div>

        <h1 class="text-3xl font-black text-slate-900 mb-3">Payment Successful! 🎉</h1>
        <p class="text-slate-500 mb-8">Congratulations! The enrollment is now complete.</p>

        <!-- Enrollment Details Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 text-left mb-8">
            @if($payment->program)
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100">
                    @if($payment->program->thumbnail_path)
                        <img src="{{ asset('storage/' . $payment->program->thumbnail_path) }}"
                            class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-[#0B4D73]">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-900">{{ $payment->program->name }}</h3>
                    <span
                        class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg">Enrolled</span>
                </div>
            </div>
            @else
            <div class="rounded-2xl border-2 border-amber-100 bg-amber-50 p-6 text-center">
                <p class="text-amber-700 font-semibold">Program information is not available</p>
            </div>
            @endif

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Student</span>
                    <span class="font-bold text-slate-700">{{ $payment->child->full_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Amount Paid</span>
                    <span class="font-bold text-emerald-600">₦{{ number_format($payment->amount) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Transaction ID</span>
                    <span class="font-mono text-xs text-slate-500">{{ $payment->transaction_id }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Date</span>
                    <span class="text-slate-700">{{ $payment->paid_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Access Status</span>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full">
                        <i class="fas fa-check-circle mr-1"></i> Immediate Access
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            @if(auth()->user()->hasRole('Parent'))
                <a href="{{ route('parent.children.show', $payment->child) }}"
                    class="block w-full py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-eye mr-2"></i> View {{ $payment->child->first_name }}'s Progress
                </a>
            @elseif($payment->program)
                <a href="{{ route('child.programs.show', $payment->program) }}"
                    class="block w-full py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-play mr-2"></i> Start Learning Now
                </a>
            @else
                <a href="{{ route('child.dashboard') }}"
                    class="block w-full py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-play mr-2"></i> Go to Dashboard
                </a>
            @endif

            <a href="{{ route('dashboard') }}"
                class="block w-full py-3 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition-all">
                Go to Dashboard
            </a>
        </div>
    </div>
@endsection