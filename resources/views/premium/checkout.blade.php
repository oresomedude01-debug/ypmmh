@extends('layouts.frontend')

@section('title', 'Complete Premium Payment')

@section('content')
<main class="pt-32 pb-24 relative overflow-hidden bg-slate-50 min-h-screen flex items-center">
    <div class="max-w-xl mx-auto px-4 sm:px-6 w-full z-10">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-amber-500 text-white shadow-xl mb-6 shadow-yellow-500/30">
                <i class="fas fa-crown text-2xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900">Premium Checkout</h1>
            <p class="text-slate-500 font-medium">Secure payment for Core Path access</p>
        </div>

        <!-- Payment Summary Card -->
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl border border-slate-100 mb-8 animate-slide-up">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-[10px] font-black uppercase tracking-widest mb-3 text-slate-400">Order Summary</h3>

                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900 capitalize">{{ $plan }} Premium</h4>
                        <p class="text-sm text-slate-500">Mentee: <span class="font-bold text-[#0B4D73]">{{ $targetChild->first_name }} {{ $targetChild->last_name }}</span></p>
                    </div>
                </div>

                <div class="flex items-center justify-between py-3 border-t border-dashed border-slate-200 mt-4">
                    <span class="text-slate-500 font-medium tracking-wide">Subscription Fee</span>
                    <span class="font-bold text-slate-900">{{ \App\Models\Setting::get('premium_currency', 'NGN') }}{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-t border-slate-200">
                    <span class="font-bold text-slate-900">Total</span>
                    <span class="text-2xl font-black text-yellow-500">{{ \App\Models\Setting::get('premium_currency', 'NGN') }}{{ number_format($payment->amount, 2) }}</span>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <i class="fas fa-shield-alt text-emerald-500"></i>
                        <span>Secured by Paystack</span>
                    </div>
                    <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">
                        ID: {{ $payment->transaction_id }}
                    </p>
                </div>

                <button type="button" id="paystack-btn" class="w-full py-4 bg-gradient-to-r from-[#0B4D73] to-blue-600 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-lock"></i>
                    <span id="pay-btn-text">Pay {{ \App\Models\Setting::get('premium_currency', 'NGN') }}{{ number_format($payment->amount, 2) }} Now</span>
                </button>

                <a href="{{ route('premium.subscribe') }}" class="mt-4 w-full py-3 text-center font-bold text-xs text-slate-500 hover:text-slate-700 transition-all block">
                    Cancel Checkout
                </a>
            </div>
        </div>

        <div class="text-center mt-6">
            <span class="opacity-30 inline-block grayscale"><img src="https://website-v3-assets.s3.amazonaws.com/assets/img/hero/Paystack-mark-white-twitter.png" alt="Paystack" class="h-6 mx-auto bg-slate-800 rounded px-2"></span>
        </div>
    </div>
</main>
@endsection

@section('scripts')
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paystackBtn = document.getElementById('paystack-btn');
            const btnText = document.getElementById('pay-btn-text');

            const paystackConfig = {
                key: "{{ $paystackPublicKey }}",
                email: "{{ $user->email }}",
                amount: {{ $payment->amount * 100 }}, // in kobo
                currency: "{{ \App\Models\Setting::get('premium_currency', 'NGN') }}",
                ref: "{{ $payment->transaction_id }}",
                metadata: {
                    payment_id: {{ $payment->id }},
                    child_id: {{ $targetChild->id }},
                    plan: "{{ $plan }}",
                    custom_fields: [
                        { display_name: "Plan", variable_name: "plan", value: "{{ $plan }} Premium" },
                        { display_name: "Student", variable_name: "student", value: "{{ $targetChild->first_name }} {{ $targetChild->last_name }}" }
                    ]
                },
                onClose: function () {
                    btnText.textContent = "Pay {{ \App\Models\Setting::get('premium_currency', 'NGN') }}{{ number_format($payment->amount, 2) }} Now";
                    paystackBtn.disabled = false;
                },
                callback: function (response) {
                    btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
                    paystackBtn.disabled = true;

                    // Submit to verification endpoint via GET with parameters
                    window.location.href = "{{ route('premium.verify') }}?payment_id={{ $payment->id }}&reference=" + response.reference;
                }
            };

            paystackBtn.addEventListener('click', function () {
                @if(empty($paystackPublicKey))
                    alert('Payment gateway is not configured. Please contact the administrator.');
                    return;
                @endif

                btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
                paystackBtn.disabled = true;

                try {
                    const handler = PaystackPop.setup(paystackConfig);
                    handler.openIframe();
                } catch (error) {
                    console.error('Paystack error:', error);
                    btnText.textContent = "Pay {{ \App\Models\Setting::get('premium_currency', 'NGN') }}{{ number_format($payment->amount, 2) }} Now";
                    paystackBtn.disabled = false;
                    alert('Payment initialization failed. Please try again.');
                }
            });
        });
    </script>
@endsection
