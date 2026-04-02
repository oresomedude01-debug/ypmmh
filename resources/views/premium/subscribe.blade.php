@extends('layouts.frontend')

@section('title', 'Subscribe to Premium')

@section('content')
<main class="pt-32 pb-24 relative overflow-hidden bg-slate-50 min-h-screen flex items-center">
    <!-- Background Decor -->
    <div class="absolute inset-0 z-0">
        <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-yellow-400/20 rounded-full blur-[120px] mix-blend-multiply"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-[#0B4D73]/20 rounded-full blur-[120px] mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('/img/grid-pattern.svg')] opacity-5"></div>
    </div>

    <!-- Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 animate-slide-up">
            <span class="px-4 py-1.5 rounded-full bg-yellow-100/50 border border-yellow-200 text-yellow-800 text-xs font-black uppercase tracking-widest shadow-sm inline-flex items-center gap-2 mb-6">
                <i class="fas fa-crown text-yellow-500"></i>
                Premium Access
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tight">
                Unlock the <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-amber-600">Core Path</span>
            </h1>
            <p class="text-lg text-slate-600 font-medium">
                Subscribe to gain full access to exclusive rolling programmes mapped precisely to your curriculum requirements.
            </p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-red-100 text-red-700 text-center rounded-xl mb-6 max-w-2xl mx-auto font-bold animate-pulse">
                {{ session('error') }}
            </div>
        @endif

        @if($user->hasRole('Parent'))
        <!-- Child Selector (For Parents Only) -->
        <div class="max-w-md mx-auto mb-12 animate-fade-in" style="animation-delay: 100ms;">
            <label class="block text-xs font-black uppercase tracking-widest text-[#0B4D73] mb-3 text-center">Select Mentee to Subscribe For</label>
            <div class="relative group">
                <select id="child_selector" onchange="window.location.href='?child_id='+this.value" class="w-full px-6 py-4 bg-white border-2 border-[#0B4D73]/20 rounded-2xl text-slate-700 font-bold focus:border-[#0B4D73] focus:ring-4 focus:ring-[#0B4D73]/10 transition-all appearance-none cursor-pointer">
                    @foreach($children as $child)
                        <option value="{{ $child->id }}" {{ $targetChild->id === $child->id ? 'selected' : '' }}>
                            {{ $child->first_name }} {{ $child->last_name }} 
                            @if($child->premium_status === 'active') (Active) @endif
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-[#0B4D73]/50 group-hover:text-[#0B4D73] transition-colors">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
            @if($targetChild->premium_status === 'active')
                <div class="text-center mt-3 text-sm font-bold text-emerald-600 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> This mentee already has an active subscription until {{ $targetChild->premium_ends_at ? $targetChild->premium_ends_at->format('M j, Y') : 'Unknown' }}.
                </div>
            @endif
        </div>
        @endif

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @php
                $currencyMap = ['NGN' => '₦', 'USD' => '$', 'GBP' => '£'];
                $currencySym = $currencyMap[$settings['premium_currency'] ?? 'NGN'] ?? '₦';
            @endphp
            <!-- Monthly -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 hover:border-slate-300 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 group flex flex-col relative overflow-hidden animate-slide-up" style="animation-delay: 200ms;">
                <h3 class="text-xl font-black text-slate-900 mb-2">Monthly</h3>
                <p class="text-sm text-slate-500 font-medium h-10">Billed every month</p>
                <div class="my-6">
                    <span class="text-4xl font-black text-slate-900">{{ $currencySym }}{{ number_format($settings['premium_price_monthly'] ?? 5000, 2) }}</span>
                </div>
                <div class="flex-1">
                    <ul class="space-y-4 text-sm font-medium text-slate-600 mb-8">
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Unlimited Rolling Programmes</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Cancel anytime</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Premium Support</li>
                    </ul>
                </div>
                <form action="{{ route('premium.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <button class="w-full py-4 bg-slate-100 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
                        Select Monthly
                    </button>
                </form>
            </div>

            <!-- Termly -->
            <div class="bg-gradient-to-br from-[#0B4D73] to-[#04334d] text-white rounded-[2rem] p-8 shadow-2xl transition-all hover:-translate-y-2 group flex flex-col relative overflow-hidden animate-slide-up" style="animation-delay: 300ms;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none -mr-10 -mt-10"></div>
                <div class="absolute top-4 right-4 px-3 py-1 bg-yellow-400 text-yellow-900 text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg">Most Popular</div>

                <h3 class="text-xl font-black text-white mb-2 relative z-10">Termly <span class="text-xs font-normal opacity-75">(4 Months)</span></h3>
                <p class="text-sm text-blue-100/70 font-medium h-10 relative z-10">Perfect for the school term</p>
                <div class="my-6 relative z-10">
                    <span class="text-5xl font-black text-white">{{ $currencySym }}{{ number_format($settings['premium_price_termly'] ?? 18000, 2) }}</span>
                </div>
                <div class="flex-1 relative z-10">
                    <ul class="space-y-4 text-sm font-medium text-blue-50 mb-8">
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-400 bg-emerald-400/10 p-1 rounded-full text-[10px]"></i> Unlimited Rolling Programmes</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-400 bg-emerald-400/10 p-1 rounded-full text-[10px]"></i> Discounted rate</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-400 bg-emerald-400/10 p-1 rounded-full text-[10px]"></i> Priority Premium Support</li>
                    </ul>
                </div>
                <form action="{{ route('premium.checkout') }}" method="POST" class="relative z-10">
                    @csrf
                    <input type="hidden" name="plan" value="termly">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <button class="w-full py-4 bg-yellow-400 text-yellow-900 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-yellow-300 transition-all shadow-lg hover:shadow-yellow-400/50">
                        Select Termly
                    </button>
                </form>
            </div>

            <!-- Annually -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 hover:border-slate-300 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 group flex flex-col relative overflow-hidden animate-slide-up" style="animation-delay: 400ms;">
                <h3 class="text-xl font-black text-slate-900 mb-2">Annually</h3>
                <p class="text-sm text-slate-500 font-medium h-10">Best value, billed once a year</p>
                <div class="my-6">
                    <span class="text-4xl font-black text-slate-900">{{ $currencySym }}{{ number_format($settings['premium_price_annually'] ?? 50000, 2) }}</span>
                </div>
                <div class="flex-1">
                    <ul class="space-y-4 text-sm font-medium text-slate-600 mb-8">
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Unlimited Rolling Programmes</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Over 20% savings</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check text-emerald-500 bg-emerald-50 p-1 rounded-full text-[10px]"></i> Exclusive Year-End Report</li>
                    </ul>
                </div>
                <form action="{{ route('premium.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="annually">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <button class="w-full py-4 bg-slate-100 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
                        Select Annually
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
