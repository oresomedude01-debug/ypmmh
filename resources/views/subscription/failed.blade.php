@extends('layouts.dashboard')

@section('title', 'Payment Failed')

@section('content')
    <div class="max-w-xl mx-auto py-12 text-center">
        <!-- Failed Icon -->
        <div
            class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-400 to-rose-500 text-white shadow-2xl shadow-red-500/40 mb-8">
            <i class="fas fa-times text-4xl"></i>
        </div>

        <h1 class="text-3xl font-black text-slate-900 mb-3">Payment Failed</h1>
        <p class="text-slate-500 mb-8">
            @if(session('error'))
                {{ session('error') }}
            @else
                We couldn't complete your payment. Please try again.
            @endif
        </p>

        <!-- Help Card -->
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-left mb-8">
            <h3 class="font-bold text-amber-800 mb-3 flex items-center gap-2">
                <i class="fas fa-lightbulb"></i> What can you do?
            </h3>
            <ul class="text-sm text-amber-700 space-y-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle mt-0.5 text-amber-500"></i>
                    <span>Check your card details and try again</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle mt-0.5 text-amber-500"></i>
                    <span>Ensure you have sufficient funds</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle mt-0.5 text-amber-500"></i>
                    <span>Try a different payment method</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle mt-0.5 text-amber-500"></i>
                    <span>Contact your bank if the issue persists</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ route('programs.explore') }}"
                class="block w-full py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-600 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="fas fa-redo mr-2"></i> Browse Programs & Try Again
            </a>

            <a href="{{ route('dashboard') }}"
                class="block w-full py-3 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition-all">
                Go to Dashboard
            </a>
        </div>

        <!-- Support Link -->
        <p class="mt-8 text-sm text-slate-400">
            Need help? <a href="mailto:support@YPMMH.com" class="text-[#0B4D73] font-bold hover:underline">Contact
                Support</a>
        </p>
    </div>
@endsection