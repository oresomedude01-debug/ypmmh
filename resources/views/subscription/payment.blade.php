@extends('layouts.dashboard')

@section('title', 'Complete Payment')

@section('content')
    <div class="max-w-xl mx-auto py-12">
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 text-white shadow-xl mb-6">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <h1 class="text-3xl font-black" style="color: var(--text-primary);">Complete Payment</h1>
            <p style="color: var(--text-secondary);">Secure payment for program enrollment</p>
        </div>

        <!-- Payment Summary Card -->
        <div class="admin-card overflow-hidden mb-8">
            <div class="p-6 border-b" style="border-color: var(--border-color);">
                <h3 class="text-[10px] font-black uppercase tracking-widest mb-3" style="color: var(--text-secondary);">
                    Order Summary</h3>

                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl overflow-hidden" style="background: var(--bg-primary);">
                        @if($program->thumbnail_path)
                            <img src="{{ asset('storage/' . $program->thumbnail_path) }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center text-[#0B4D73]">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold" style="color: var(--text-primary);">{{ $program->name }}</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Enrolling: <span
                                class="font-bold text-[#0B4D73]">{{ $child->full_name }}</span></p>
                    </div>
                </div>

                <div class="flex items-center justify-between py-3 border-t border-dashed"
                    style="border-color: var(--border-color);">
                    <span style="color: var(--text-secondary);">Program Fee</span>
                    <span class="font-bold" style="color: var(--text-primary);">₦{{ number_format($program->price) }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-t" style="border-color: var(--border-color);">
                    <span class="font-bold" style="color: var(--text-primary);">Total</span>
                    <span class="text-2xl font-black text-[#0B4D73]">₦{{ number_format($payment->amount) }}</span>
                </div>
            </div>

            <div class="p-6" style="background: var(--bg-primary);">
                <div class="flex items-center gap-3 text-sm mb-4" style="color: var(--text-secondary);">
                    <i class="fas fa-shield-alt text-emerald-500"></i>
                    <span>Secure payment powered by Paystack</span>
                </div>

                <p class="text-xs mb-6" style="color: var(--text-secondary);">
                    Transaction ID: <span class="font-mono font-bold">{{ $payment->transaction_id }}</span>
                </p>

                <!-- Paystack Payment Button -->
                <button type="button" id="paystack-btn"
                    class="w-full py-4 bg-gradient-to-r from-emerald-500 to-green-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-lock"></i>
                    <span id="pay-btn-text">Pay ₦{{ number_format($payment->amount) }} Now</span>
                </button>

                <a href="{{ route('parent.dashboard') }}"
                    class="mt-3 w-full py-3 border text-center font-medium rounded-xl hover:opacity-80 transition-all text-sm block"
                    style="background: var(--bg-secondary); border-color: var(--border-color); color: var(--text-secondary);">
                    Cancel Payment
                </a>
            </div>
        </div>

        <div class="text-center text-xs" style="color: var(--text-secondary);">
            <i class="fas fa-lock mr-1"></i> Your payment information is encrypted and secure
        </div>
    </div>

    <!-- Paystack Logo -->
    <div class="text-center mt-6">
        <img src="https://website-v3-assets.s3.amazonaws.com/assets/img/hero/Paystack-mark-white-twitter.png" alt="Paystack"
            class="h-6 mx-auto opacity-50">
    </div>
@endsection

@section('scripts')
    <!-- Paystack Inline JS -->
    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paystackBtn = document.getElementById('paystack-btn');
            const btnText = document.getElementById('pay-btn-text');

            // Paystack configuration from server
            const paystackConfig = {
                key: "{{ $paystackPublicKey }}",
                email: "{{ $payerEmail }}",
                amount: {{ $payment->amount * 100 }}, // Paystack expects amount in kobo
                currency: "NGN",
                ref: "{{ $payment->transaction_id }}",
                metadata: {
                    payment_id: {{ $payment->id }},
                    program_id: {{ $program->id }},
                    child_id: {{ $child->id }},
                    custom_fields: [
                        {
                            display_name: "Program",
                            variable_name: "program",
                            value: "{{ $program->name }}"
                        },
                        {
                            display_name: "Student",
                            variable_name: "student",
                            value: "{{ $child->full_name }}"
                        }
                    ]
                },
                onClose: function () {
                    // User closed the payment popup
                    btnText.textContent = 'Pay ₦{{ number_format($payment->amount) }} Now';
                    paystackBtn.disabled = false;
                },
                callback: function (response) {
                    // Payment successful - verify on server
                    btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
                    paystackBtn.disabled = true;

                    // Submit to verification endpoint
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('subscription.verify-payment') }}";

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = "{{ csrf_token() }}";
                    form.appendChild(csrfInput);

                    const paymentIdInput = document.createElement('input');
                    paymentIdInput.type = 'hidden';
                    paymentIdInput.name = 'payment_id';
                    paymentIdInput.value = "{{ $payment->id }}";
                    form.appendChild(paymentIdInput);

                    const referenceInput = document.createElement('input');
                    referenceInput.type = 'hidden';
                    referenceInput.name = 'reference';
                    referenceInput.value = response.reference;
                    form.appendChild(referenceInput);

                    document.body.appendChild(form);
                    form.submit();
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
                    btnText.textContent = 'Pay ₦{{ number_format($payment->amount) }} Now';
                    paystackBtn.disabled = false;
                    alert('Payment initialization failed. Please try again.');
                }
            });
        });
    </script>
@endsection