<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Entry point: User clicks "Subscribe" on a program
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $program = Program::findOrFail($request->program_id);

        // Store program intent in session (survives login/register redirects)
        session([
            'subscription_intent' => [
                'program_id' => $program->id,
                'initiated_at' => now()->toISOString(),
            ]
        ]);

        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please log in to subscribe to "' . $program->name . '". Don\'t have an account? <a href="' . route('enroll') . '" class="underline font-bold">Register here</a>.');
        }

        return $this->processAuthenticatedUser($program);
    }

    /**
     * Resume subscription after login/register
     */
    public function resumeAfterLogin(Request $request)
    {
        $intent = session('subscription_intent');

        if (!$intent) {
            return redirect()->route('dashboard')
                ->with('info', 'No pending subscription found.');
        }

        $program = Program::find($intent['program_id']);

        if (!$program) {
            session()->forget('subscription_intent');
            return redirect()->route('dashboard')
                ->with('error', 'The program you were subscribing to is no longer available.');
        }

        return $this->processAuthenticatedUser($program);
    }

    /**
     * Process authenticated user subscription
     */
    protected function processAuthenticatedUser(Program $program)
    {
        $user = Auth::user();

        // Check if user is a Child
        if ($user->hasRole('Child')) {
            // Check if already enrolled
            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where('program_id', $program->id)
                ->first();

            if ($existingEnrollment) {
                session()->forget('subscription_intent');
                return redirect()->route('child.programs.show', $program)
                    ->with('info', 'You are already enrolled in this program.');
            }

            // Skip child selection, go directly to payment
            return $this->initiatePayment($program, $user->id, $user->parent_id ?? $user->id);
        }

        // User is a Parent
        if ($user->hasRole('Parent')) {
            $children = $user->children;

            if ($children->isEmpty()) {
                // No children - redirect to add child
                session(['subscription_intent.needs_child' => true]);
                return redirect()->route('parent.children.create')
                    ->with('warning', 'You need to add a child before subscribing to a program.');
            }

            if ($children->count() === 1) {
                // Only one child - auto-select
                $child = $children->first();

                // Check if already enrolled
                $existingEnrollment = Enrollment::where('user_id', $child->id)
                    ->where('program_id', $program->id)
                    ->first();

                if ($existingEnrollment) {
                    session()->forget('subscription_intent');
                    return redirect()->route('parent.children.show', $child)
                        ->with('info', $child->first_name . ' is already enrolled in this program.');
                }

                return $this->initiatePayment($program, $child->id, $user->id);
            }

            // Multiple children - show selection
            return view('subscription.select-child', [
                'program' => $program,
                'children' => $children,
            ]);
        }

        // Unknown role
        session()->forget('subscription_intent');
        return redirect()->route('dashboard')
            ->with('error', 'Your account type cannot subscribe to programs.');
    }

    /**
     * Parent selects child for subscription
     */
    public function selectChild(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'child_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $program = Program::findOrFail($request->program_id);
        $child = User::findOrFail($request->child_id);

        // Verify the child belongs to this parent
        if ($child->parent_id !== $user->id) {
            return back()->with('error', 'Invalid child selection.');
        }

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $child->id)
            ->where('program_id', $program->id)
            ->first();

        if ($existingEnrollment) {
            session()->forget('subscription_intent');
            return redirect()->route('parent.children.show', $child)
                ->with('info', $child->first_name . ' is already enrolled in this program.');
        }

        return $this->initiatePayment($program, $child->id, $user->id);
    }

    /**
     * Create payment record and show Paystack payment page
     */
    protected function initiatePayment(Program $program, int $childId, int $payerId)
    {
        $payer = User::find($payerId);
        $child = User::find($childId);

        // Create pending payment record
        $payment = Payment::create([
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'user_id' => $payerId,
            'child_id' => $childId,
            'program_id' => $program->id,
            'amount' => $program->price,
            'currency' => 'NGN',
            'status' => 'pending',
            'payment_method' => 'paystack',
        ]);

        // Store payment reference in session
        session(['active_payment_id' => $payment->id]);

        // Get Paystack public key from settings
        $paystackPublicKey = Setting::get('paystack_public_key', '');

        return view('subscription.payment', [
            'payment' => $payment,
            'program' => $program,
            'child' => $child,
            'paystackPublicKey' => $paystackPublicKey,
            'payerEmail' => $payer->email,
        ]);
    }

    /**
     * Verify payment via Paystack API
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'reference' => 'required|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Verify payment belongs to current user
        if ($payment->user_id !== Auth::id() && $payment->child_id !== Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized payment verification.');
        }

        // Get Paystack secret key from settings
        $paystackSecretKey = Setting::get('paystack_secret_key', '');

        if (empty($paystackSecretKey)) {
            Log::error('Paystack secret key not configured');
            return redirect()->route('subscription.failed')
                ->with('error', 'Payment gateway not properly configured. Please contact support.');
        }

        try {
            // Verify payment with Paystack API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paystackSecretKey,
                'Content-Type' => 'application/json',
            ])->get('https://api.paystack.co/transaction/verify/' . $request->reference);

            $result = $response->json();

            Log::info('Paystack verification response', ['reference' => $request->reference, 'response' => $result]);

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                $data = $result['data'];

                // Check if payment was successful
                if ($data['status'] === 'success') {
                    // Verify amount matches (Paystack returns amount in kobo)
                    $expectedAmount = $payment->amount * 100;
                    if ($data['amount'] !== $expectedAmount) {
                        Log::warning('Payment amount mismatch', [
                            'expected' => $expectedAmount,
                            'received' => $data['amount'],
                            'payment_id' => $payment->id,
                        ]);
                    }

                    // Update payment record
                    $payment->update([
                        'status' => 'success',
                        'gateway_reference' => $data['reference'],
                        'paid_at' => now(),
                    ]);

                    // Create enrollment
                    Enrollment::create([
                        'user_id' => $payment->child_id,
                        'program_id' => $payment->program_id,
                        'status' => 'active',
                        'is_active' => true,
                        'payment_id' => $payment->id,
                    ]);

                    // Clear session
                    session()->forget(['subscription_intent', 'active_payment_id']);

                    return redirect()->route('subscription.success', $payment);
                }
            }

            // Payment failed or verification failed
            $payment->update([
                'status' => 'failed',
                'gateway_reference' => $request->reference,
            ]);

            Log::warning('Payment verification failed', [
                'payment_id' => $payment->id,
                'reference' => $request->reference,
                'response' => $result,
            ]);

            return redirect()->route('subscription.failed')
                ->with('error', 'Payment verification failed. If you were charged, please contact support.');

        } catch (\Exception $e) {
            Log::error('Paystack verification exception', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('subscription.failed')
                ->with('error', 'An error occurred while verifying your payment. Please contact support.');
        }
    }

    /**
     * Paystack webhook handler
     */
    public function handleWebhook(Request $request)
    {
        // Verify webhook signature
        $paystackSecretKey = Setting::get('paystack_secret_key', '');
        $signature = $request->header('x-paystack-signature');

        if (!$signature) {
            Log::warning('Paystack webhook: Missing signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $computedSignature = hash_hmac('sha512', $request->getContent(), $paystackSecretKey);

        if ($signature !== $computedSignature) {
            Log::warning('Paystack webhook: Signature mismatch');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        Log::info('Paystack webhook received', ['event' => $event, 'data' => $payload]);

        if ($event === 'charge.success') {
            $data = $payload['data'];
            $reference = $data['reference'];

            // Find payment by transaction_id
            $payment = Payment::where('transaction_id', $reference)->first();

            if ($payment && $payment->status === 'pending') {
                $payment->update([
                    'status' => 'success',
                    'gateway_reference' => $reference,
                    'paid_at' => now(),
                ]);

                // Create enrollment if not exists
                $existingEnrollment = Enrollment::where('user_id', $payment->child_id)
                    ->where('program_id', $payment->program_id)
                    ->first();

                if (!$existingEnrollment) {
                    Enrollment::create([
                        'user_id' => $payment->child_id,
                        'program_id' => $payment->program_id,
                        'status' => 'active',
                        'is_active' => true,
                        'payment_id' => $payment->id,
                    ]);
                }

                Log::info('Payment confirmed via webhook', ['payment_id' => $payment->id]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Success page
     */
    public function success(Payment $payment)
    {
        // Verify access
        if ($payment->user_id !== Auth::id() && $payment->child_id !== Auth::id()) {
            abort(403);
        }

        $payment->load(['program', 'child', 'user']);

        return view('subscription.success', [
            'payment' => $payment,
        ]);
    }

    /**
     * Failed page
     */
    public function failed()
    {
        return view('subscription.failed');
    }
}
