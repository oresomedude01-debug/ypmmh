@extends('layouts.dashboard')

@section('title', 'Subscription Settings')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                Subscription Settings
            </h1>
            <p class="font-medium text-slate-500 mt-1">Manage auto-renewal preferences for your mentees</p>
        </div>
        <a href="{{ route('premium.subscribe') }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 text-yellow-900 font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg hover:shadow-amber-400/40 hover:-translate-y-0.5 transition-all">
            <i class="fas fa-crown"></i>
            Subscribe / Renew
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Child Selector --}}
    @if($children->count() > 1)
    <div class="admin-card p-6">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Select Mentee</label>
        <div class="relative">
            <select id="child_selector"
                onchange="window.location.href='?child_id='+this.value"
                class="w-full px-6 py-4 bg-white border-2 border-[#0B4D73]/20 rounded-2xl text-slate-700 font-bold focus:border-[#0B4D73] transition-all appearance-none cursor-pointer">
                @foreach($children as $child)
                    <option value="{{ $child->id }}" {{ $targetChild->id === $child->id ? 'selected' : '' }}>
                        {{ $child->full_name }}
                        @if($child->premium_status === 'active' && $child->premium_ends_at?->isFuture()) (Active Premium) @endif
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-[#0B4D73]/50">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
    @endif

    {{-- Subscription Status Card --}}
    <div class="admin-card p-8 space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                <i class="fas fa-crown text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900">{{ $targetChild->full_name }}</h3>
                <p class="text-sm text-slate-500">Subscription details &amp; preferences</p>
            </div>
        </div>

        {{-- Status Banner --}}
        @php
            $status      = $targetChild->computed_premium_status;
            $isActive    = $status === 'active';
            $isTrial     = $status === 'trial';
            $isExpired   = $status === 'expired';
        @endphp

        <div class="rounded-2xl p-5 border-2 flex items-center gap-4
            {{ $isActive ? 'bg-emerald-50 border-emerald-200' : ($isTrial ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-200') }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg
                {{ $isActive ? 'bg-emerald-500 text-white' : ($isTrial ? 'bg-blue-500 text-white' : 'bg-slate-300 text-white') }}">
                <i class="fas {{ $isActive ? 'fa-check' : ($isTrial ? 'fa-flask' : 'fa-times') }}"></i>
            </div>
            <div class="flex-1">
                @if($isActive)
                    <p class="font-black text-emerald-700">Active Premium</p>
                    <p class="text-sm text-emerald-600 font-medium">
                        Expires <strong>{{ $targetChild->premium_ends_at?->format('F j, Y') }}</strong>
                        ({{ $targetChild->premium_ends_at?->diffForHumans() }})
                    </p>
                @elseif($isTrial)
                    <p class="font-black text-blue-700">Trial Period</p>
                    <p class="text-sm text-blue-600 font-medium">
                        Ends <strong>{{ $targetChild->trial_ends_at?->format('F j, Y') }}</strong>
                        ({{ $targetChild->trial_ends_at?->diffForHumans() }})
                    </p>
                @elseif($isExpired)
                    <p class="font-black text-red-700">Subscription Expired</p>
                    <p class="text-sm text-red-600 font-medium">
                        Expired on {{ $targetChild->premium_ends_at?->format('F j, Y') }}
                    </p>
                @else
                    <p class="font-black text-slate-700">No Active Subscription</p>
                    <p class="text-sm text-slate-500 font-medium">Subscribe below to unlock premium features</p>
                @endif
            </div>
            @if($isActive)
                <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-200">
                    {{ ucfirst($targetChild->premium_plan ?? '') }}
                </span>
            @endif
        </div>

        {{-- Auto-Renewal Toggle --}}
        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-start justify-between gap-6">
                <div class="flex-1">
                    <h4 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fas fa-sync-alt text-[#0B4D73]"></i>
                        Auto-Renewal
                    </h4>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        When enabled, we'll remind you before the subscription expires so you can renew promptly.
                        @if($isActive)
                            <br><span class="text-emerald-600 font-medium">Your subscription will be active until
                            <strong>{{ $targetChild->premium_ends_at?->format('M j, Y') }}</strong>.</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('premium.auto-renewal.update') }}" method="POST" id="autoRenewalForm-{{ $targetChild->id }}">
                    @csrf
                    <input type="hidden" name="child_id" value="{{ $targetChild->id }}">
                    <input type="hidden" name="auto_renewal_enabled" id="arInput-{{ $targetChild->id }}"
                        value="{{ $targetChild->auto_renewal_enabled ? '1' : '0' }}">
                    <button type="button"
                        id="arToggle-{{ $targetChild->id }}"
                        onclick="toggleAR({{ $targetChild->id }}, {{ $targetChild->auto_renewal_enabled ? 'true' : 'false' }})"
                        class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none
                            {{ $targetChild->auto_renewal_enabled ? 'bg-emerald-500' : 'bg-slate-200' }}"
                        role="switch"
                        aria-checked="{{ $targetChild->auto_renewal_enabled ? 'true' : 'false' }}">
                        <span class="sr-only">Toggle auto-renewal</span>
                        <span id="arThumb-{{ $targetChild->id }}"
                            class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-lg ring-0 transition duration-300
                                {{ $targetChild->auto_renewal_enabled ? 'translate-x-6' : 'translate-x-0' }}">
                        </span>
                    </button>
                </form>
            </div>

            <div id="arStatus-{{ $targetChild->id }}"
                class="mt-3 text-xs font-bold {{ $targetChild->auto_renewal_enabled ? 'text-emerald-600' : 'text-slate-400' }}">
                <i class="fas {{ $targetChild->auto_renewal_enabled ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                Auto-renewal is {{ $targetChild->auto_renewal_enabled ? 'enabled' : 'disabled' }}
            </div>
        </div>
    </div>

    {{-- All Children Summary (quick view) --}}
    @if($children->count() > 1)
    <div class="admin-card p-8">
        <h3 class="font-black text-slate-900 mb-6 flex items-center gap-2">
            <i class="fas fa-users text-[#0B4D73]"></i>
            All Mentees Overview
        </h3>
        <div class="space-y-3">
            @foreach($children as $child)
            @php $cs = $child->computed_premium_status; @endphp
            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl {{ $cs === 'active' ? 'bg-emerald-100 text-emerald-600' : ($cs === 'trial' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-400') }} flex items-center justify-center text-sm font-black">
                        {{ strtoupper(substr($child->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 text-sm">{{ $child->full_name }}</p>
                        <p class="text-xs text-slate-500">
                            @if($cs === 'active')
                                <span class="text-emerald-600">Active until {{ $child->premium_ends_at?->format('M j, Y') }}</span>
                            @elseif($cs === 'trial')
                                <span class="text-blue-600">Trial until {{ $child->trial_ends_at?->format('M j, Y') }}</span>
                            @elseif($cs === 'expired')
                                <span class="text-red-500">Expired</span>
                            @else
                                <span class="text-slate-400">No subscription</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs {{ $child->auto_renewal_enabled ? 'text-emerald-600' : 'text-slate-400' }} font-bold hidden sm:block">
                        <i class="fas fa-sync-alt mr-1"></i>
                        {{ $child->auto_renewal_enabled ? 'Auto-renewal on' : 'Auto-renewal off' }}
                    </span>
                    <a href="?child_id={{ $child->id }}"
                        class="text-[10px] font-black uppercase tracking-widest px-3 py-2 rounded-xl {{ $targetChild->id === $child->id ? 'bg-[#0B4D73] text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-[#0B4D73] hover:text-[#0B4D73]' }} transition-all">
                        {{ $targetChild->id === $child->id ? 'Viewing' : 'Manage' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA --}}
    @if(!$isActive)
    <div class="admin-card p-8 text-center bg-gradient-to-br from-[#0B4D73]/5 to-yellow-50 border-yellow-200">
        <i class="fas fa-crown text-4xl text-yellow-500 mb-4 block"></i>
        <h3 class="font-black text-slate-900 text-xl mb-2">Unlock Premium for {{ $targetChild->first_name }}</h3>
        <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">
            Get unlimited access to rolling programmes mapped to curriculum requirements.
        </p>
        <a href="{{ route('premium.subscribe', ['child_id' => $targetChild->id]) }}"
            class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-yellow-400 to-amber-500 text-yellow-900 font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg hover:shadow-amber-400/40 hover:-translate-y-0.5 transition-all">
            <i class="fas fa-crown"></i>
            Subscribe Now
        </a>
    </div>
    @endif

</div>

<script>
function toggleAR(childId, currentState) {
    const newState = !currentState;
    const btn      = document.getElementById('arToggle-' + childId);
    const thumb    = document.getElementById('arThumb-' + childId);
    const input    = document.getElementById('arInput-' + childId);
    const status   = document.getElementById('arStatus-' + childId);

    // Update UI instantly
    btn.setAttribute('aria-checked', newState.toString());
    if (newState) {
        btn.classList.remove('bg-slate-200');
        btn.classList.add('bg-emerald-500');
        thumb.classList.remove('translate-x-0');
        thumb.classList.add('translate-x-6');
        status.className = 'mt-3 text-xs font-bold text-emerald-600';
        status.innerHTML = '<i class="fas fa-check-circle"></i> Auto-renewal is enabled';
    } else {
        btn.classList.remove('bg-emerald-500');
        btn.classList.add('bg-slate-200');
        thumb.classList.remove('translate-x-6');
        thumb.classList.add('translate-x-0');
        status.className = 'mt-3 text-xs font-bold text-slate-400';
        status.innerHTML = '<i class="fas fa-times-circle"></i> Auto-renewal is disabled';
    }

    // Update input and submit
    input.value = newState ? '1' : '0';
    document.getElementById('autoRenewalForm-' + childId).submit();
}
</script>
@endsection
