@extends('layouts.dashboard')

@section('title', 'Subscribe to Premium')

@section('content')
<main class="pt-12 pb-24 relative overflow-hidden bg-slate-50 min-h-screen">
    <!-- Background Decor -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-1/4 -right-1/4 w-1/2 h-1/2 bg-yellow-400/20 rounded-full blur-[120px] mix-blend-multiply"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-1/2 h-1/2 bg-[#0B4D73]/20 rounded-full blur-[120px] mix-blend-multiply"></div>
    </div>

    <!-- Confirmation Modal (shown when child already has active sub) -->
    <div id="confirmModal"
        class="{{ session('needs_confirmation') ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full p-8 animate-slide-up">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 text-2xl mx-auto mb-6">
                <i class="fas fa-crown"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 text-center mb-2">Already Subscribed</h3>
            <p class="text-sm text-slate-600 text-center leading-relaxed mb-2">
                <strong id="modalChildName"></strong> already has an active premium subscription until
                <strong id="modalExpiry" class="text-emerald-600"></strong>.
            </p>
            <p class="text-sm text-slate-600 text-center leading-relaxed mb-6">
                Adding a new subscription will <strong class="text-[#0B4D73]">stack on top</strong> — it starts
                counting only after the current one expires. You won't lose any existing access.
            </p>
            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-200 mb-6 text-xs text-blue-700 font-medium">
                <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                New subscription will begin from <strong id="modalStackDate" class="text-blue-800"></strong>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeConfirmModal()"
                    class="py-3 bg-slate-100 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-all">
                    Cancel
                </button>
                <button onclick="proceedWithPayment()"
                    class="py-3 bg-gradient-to-r from-[#0B4D73] to-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:shadow-lg transition-all">
                    Proceed Anyway
                </button>
            </div>
        </div>
    </div>

    <!-- Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 animate-slide-up">
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
            <div class="p-4 bg-red-100 text-red-700 text-center rounded-xl mb-6 max-w-2xl mx-auto font-bold">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($user->hasRole('Parent'))
        <!-- Child Selector (For Parents Only) -->
        <div class="max-w-md mx-auto mb-12 animate-fade-in" style="animation-delay: 100ms;">
            <label class="block text-xs font-black uppercase tracking-widest text-[#0B4D73] mb-3 text-center">Select Mentee to Subscribe For</label>
            <div class="relative group">
                <select id="child_selector"
                    onchange="window.location.href='?child_id='+this.value"
                    class="w-full px-6 py-4 bg-white border-2 border-[#0B4D73]/20 rounded-2xl text-slate-700 font-bold focus:border-[#0B4D73] focus:ring-4 focus:ring-[#0B4D73]/10 transition-all appearance-none cursor-pointer">
                    @foreach($children as $child)
                        <option value="{{ $child->id }}" {{ $targetChild->id === $child->id ? 'selected' : '' }}>
                            {{ $child->first_name }} {{ $child->last_name }}
                            @if($child->premium_status === 'active' && $child->premium_ends_at?->isFuture()) (Active Premium) @endif
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-[#0B4D73]/50 group-hover:text-[#0B4D73] transition-colors">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>

            @php
                $childHasActive = $targetChild->premium_status === 'active'
                    && $targetChild->premium_ends_at
                    && $targetChild->premium_ends_at->isFuture();
            @endphp

            @if($childHasActive)
                <div class="mt-3 p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                    <p class="text-sm font-bold text-emerald-700 flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        Active premium until <strong>{{ $targetChild->premium_ends_at->format('M j, Y') }}</strong>
                    </p>
                    <p class="text-xs text-emerald-600 mt-1 ml-5">
                        Any new plan purchased will <strong>stack</strong> and start from that date.
                    </p>
                </div>
            @endif
        </div>
        @endif

        @php
            $childHasActive = isset($childHasActive) ? $childHasActive
                : ($targetChild->premium_status === 'active'
                    && $targetChild->premium_ends_at
                    && $targetChild->premium_ends_at->isFuture());
        @endphp

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @php
                $currencyMap = ['NGN' => '₦', 'USD' => '$', 'GBP' => '£'];
                $currencySym = $currencyMap[$settings['premium_currency'] ?? 'NGN'] ?? '₦';

                // JS data for confirmation modal
                $childExpiry    = $targetChild->premium_ends_at?->format('M j, Y') ?? '';
                $childExpiryJs  = $targetChild->premium_ends_at?->toDateString() ?? '';
                $childName      = $targetChild->first_name . ' ' . $targetChild->last_name;
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
                <form action="{{ route('premium.checkout') }}" method="POST" class="plan-form" data-plan="monthly">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <input type="hidden" name="confirmed" value="0" class="confirmed-input">
                    <button type="submit" class="w-full py-4 bg-slate-100 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
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
                <form action="{{ route('premium.checkout') }}" method="POST" class="relative z-10 plan-form" data-plan="termly">
                    @csrf
                    <input type="hidden" name="plan" value="termly">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <input type="hidden" name="confirmed" value="0" class="confirmed-input">
                    <button type="submit" class="w-full py-4 bg-yellow-400 text-yellow-900 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-yellow-300 transition-all shadow-lg hover:shadow-yellow-400/50">
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
                <form action="{{ route('premium.checkout') }}" method="POST" class="plan-form" data-plan="annually">
                    @csrf
                    <input type="hidden" name="plan" value="annually">
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <input type="hidden" name="confirmed" value="0" class="confirmed-input">
                    <button type="submit" class="w-full py-4 bg-slate-100 text-slate-700 rounded-2xl font-black uppercase tracking-widest text-[10px] group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
                        Select Annually
                    </button>
                </form>
            </div>
        </div>

        {{-- Auto-Renewal Preference (shown on this page for logged-in parents) --}}
        @if($user->hasRole('Parent') && $targetChild)
        <div class="max-w-5xl mx-auto mt-10 animate-fade-in" style="animation-delay:500ms">
            <div class="bg-white rounded-[2rem] p-7 border border-slate-100 shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B4D73] shrink-0">
                        <i class="fas fa-sync-alt text-lg"></i>
                    </div>
                    <div>
                        <p class="font-black text-slate-900">Auto-Renewal for {{ $targetChild->first_name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">We'll notify you before expiry so you can renew on time.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:shrink-0">
                    <span id="inlineArLabel" class="text-xs font-bold {{ $targetChild->auto_renewal_enabled ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $targetChild->auto_renewal_enabled ? 'Enabled' : 'Disabled' }}
                    </span>
                    <form action="{{ route('premium.auto-renewal.update') }}" method="POST" id="inlineARForm">
                        @csrf
                        <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                        <input type="hidden" name="auto_renewal_enabled" id="inlineARInput"
                            value="{{ $targetChild->auto_renewal_enabled ? '1' : '0' }}">
                        <button type="button" id="inlineARToggle"
                            onclick="toggleInlineAR({{ $targetChild->auto_renewal_enabled ? 'true' : 'false' }})"
                            class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300
                                {{ $targetChild->auto_renewal_enabled ? 'bg-emerald-500' : 'bg-slate-200' }}"
                            role="switch" aria-checked="{{ $targetChild->auto_renewal_enabled ? 'true' : 'false' }}">
                            <span class="sr-only">Toggle auto-renewal</span>
                            <span id="inlineARThumb"
                                class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-lg transition duration-300
                                    {{ $targetChild->auto_renewal_enabled ? 'translate-x-6' : 'translate-x-0' }}">
                            </span>
                        </button>
                    </form>
                    <a href="{{ route('premium.settings') }}"
                        class="text-[10px] font-black uppercase tracking-widest px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-[#0B4D73] hover:text-white transition-all">
                        All Settings
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

<script>
    // ---------- Confirmation modal helpers ----------
    const hasActiveSub = {{ $childHasActive ? 'true' : 'false' }};
    const childName    = @json($childName);
    const childExpiry  = @json($childExpiry);
    let   pendingForm  = null;

    document.querySelectorAll('.plan-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (hasActiveSub) {
                e.preventDefault();
                pendingForm = this;
                document.getElementById('modalChildName').textContent = childName;
                document.getElementById('modalExpiry').textContent    = childExpiry;
                document.getElementById('modalStackDate').textContent  = childExpiry;
                document.getElementById('confirmModal').classList.remove('hidden');
                document.getElementById('confirmModal').classList.add('flex');
            }
        });
    });

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.getElementById('confirmModal').classList.remove('flex');
        pendingForm = null;
    }

    function proceedWithPayment() {
        if (pendingForm) {
            pendingForm.querySelector('.confirmed-input').value = '1';
            pendingForm.submit();
        }
    }

    // ---------- Inline auto-renewal toggle ----------
    function toggleInlineAR(current) {
        const newState = !current;
        const btn      = document.getElementById('inlineARToggle');
        const thumb    = document.getElementById('inlineARThumb');
        const input    = document.getElementById('inlineARInput');
        const label    = document.getElementById('inlineArLabel');

        btn.setAttribute('aria-checked', newState.toString());
        if (newState) {
            btn.classList.replace('bg-slate-200', 'bg-emerald-500');
            thumb.classList.replace('translate-x-0', 'translate-x-6');
            label.className = 'text-xs font-bold text-emerald-600';
            label.textContent = 'Enabled';
        } else {
            btn.classList.replace('bg-emerald-500', 'bg-slate-200');
            thumb.classList.replace('translate-x-6', 'translate-x-0');
            label.className = 'text-xs font-bold text-slate-400';
            label.textContent = 'Disabled';
        }
        input.value = newState ? '1' : '0';
        document.getElementById('inlineARForm').submit();
    }
</script>
@endsection
