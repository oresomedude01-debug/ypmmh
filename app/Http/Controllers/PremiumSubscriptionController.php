<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PremiumSubscriptionController extends Controller
{
    /**
     * Show subscription plans
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $settings = Setting::whereIn('key', [
            'premium_price_monthly', 
            'premium_price_termly', 
            'premium_price_annually', 
            'premium_currency',
            'premium_trial_enabled',
            'trial_duration_days',
        ])->pluck('value', 'key');

        if ($user->hasRole('Child')) {
            $age = Carbon::parse($user->date_of_birth)->age;
            if ($age < 16) {
                return redirect()->route('child.dashboard')
                    ->with('error', 'You must be at least 16 years old to subscribe. Please ask your parent.');
            }
            $targetChild = $user;
            $children = collect([$user]);
        } elseif ($user->hasRole('Parent')) {
            $children = $user->children;
            if ($children->isEmpty()) {
                return redirect()->route('parent.dashboard')
                    ->with('warning', 'You have no mentees listed. Add a mentee first.');
            }
            $targetChild = $request->child_id ? User::find($request->child_id) : $children->first();
            
            if (!$targetChild || $targetChild->parent_id !== $user->id) {
                abort(403, 'Invalid child selected.');
            }
        } else {
            return redirect()->route('dashboard')->with('error', 'Only parents and children can subscribe.');
        }

        return view('premium.subscribe', compact('settings', 'targetChild', 'children', 'user'));
    }

    /**
     * Show standalone subscription settings page (auto-renewal management).
     * Parents can access this without making a new payment.
     */
    public function subscriptionSettings(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('Parent')) {
            abort(403, 'Only parents can manage subscription settings here.');
        }

        $children = $user->children;

        if ($children->isEmpty()) {
            return redirect()->route('parent.dashboard')
                ->with('warning', 'You have no mentees listed.');
        }

        $targetChild = $request->child_id ? User::find($request->child_id) : $children->first();

        if (!$targetChild || $targetChild->parent_id !== $user->id) {
            abort(403, 'Invalid child selected.');
        }

        return view('premium.settings', compact('user', 'children', 'targetChild'));
    }

    /**
     * Update auto-renewal via standard form POST (non-AJAX).
     */
    public function updateAutoRenewal(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'child_id'            => 'required|exists:users,id',
            'auto_renewal_enabled' => 'required|in:0,1',
        ]);

        $targetUser = User::findOrFail($request->child_id);

        if (!$user->hasRole('Parent') || $targetUser->parent_id !== $user->id) {
            abort(403);
        }

        $targetUser->auto_renewal_enabled = (bool) $request->auto_renewal_enabled;
        $targetUser->save();

        $status = $targetUser->auto_renewal_enabled ? 'enabled' : 'disabled';
        return back()->with('success', "Auto-renewal has been {$status} for {$targetUser->first_name}.");
    }

    /**
     * Handle checkout creation
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan'     => 'required|in:monthly,termly,annually',
            'child_id' => 'required|exists:users,id',
            // 'confirmed' is set by the JS modal when parent acknowledges active sub
            'confirmed' => 'nullable|in:1',
        ]);

        $user        = Auth::user();
        $targetChild = User::findOrFail($request->child_id);

        if ($user->hasRole('Child') && $user->id !== $targetChild->id) {
            abort(403);
        }

        if ($user->hasRole('Parent') && $targetChild->parent_id !== $user->id) {
            abort(403);
        }

        // If child already has an active subscription and parent has not confirmed, bounce back
        // The view handles the confirmation modal; we double-check here as a safety net.
        $hasActiveSub = $targetChild->premium_status === 'active'
            && $targetChild->premium_ends_at
            && $targetChild->premium_ends_at->isFuture();

        if ($hasActiveSub && !$request->confirmed) {
            return back()->with('needs_confirmation', true)
                ->with('pending_plan', $request->plan)
                ->with('pending_child_id', $request->child_id);
        }

        $plan     = $request->plan;
        $priceKey = 'premium_price_' . $plan;
        $price    = Setting::get($priceKey, 0);

        if ($price <= 0) {
            return back()->with('error', 'Premium pricing is not configured. Please contact support.');
        }

        // Build a descriptive label that notes this is a stacked renewal
        $description = 'Premium Subscription - ' . ucfirst($plan) . ' Plan';
        if ($hasActiveSub) {
            $description .= ' (Renewal — starts ' . $targetChild->premium_ends_at->format('M j, Y') . ')';
        }

        $payment = Payment::create([
            'transaction_id' => 'PRM-' . strtoupper(Str::random(12)),
            'user_id'        => $user->id,
            'child_id'       => $targetChild->id,
            'amount'         => $price,
            'currency'       => Setting::get('premium_currency', 'NGN'),
            'status'         => 'pending',
            'payment_method' => 'paystack',
            'description'    => $description,
        ]);

        session(['payment_premium_plan_' . $payment->id => $plan]);

        $paystackPublicKey = Setting::get('paystack_public_key', '');

        if (!$paystackPublicKey) {
            return back()->with('error', 'Payment gateway is not fully configured.');
        }

        return view('premium.checkout', compact('payment', 'paystackPublicKey', 'user', 'targetChild', 'plan', 'hasActiveSub'));
    }

    /**
     * Verify Paystack payment
     */
    public function verify(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'reference'  => 'required|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $paystackSecretKey = Setting::get('paystack_secret_key', '');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paystackSecretKey,
                'Content-Type'  => 'application/json',
            ])->get('https://api.paystack.co/transaction/verify/' . $request->reference);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                if ($result['data']['status'] === 'success') {
                    $payment->update([
                        'status'            => 'success',
                        'gateway_reference' => $result['data']['reference'],
                        'paid_at'           => now(),
                    ]);

                    $plan        = session('payment_premium_plan_' . $payment->id, 'monthly');
                    $targetChild = User::find($payment->child_id);

                    if ($targetChild) {
                        $wasRenewal = $targetChild->premium_ends_at && $targetChild->premium_ends_at->isFuture();

                        // grantPremium stacks from the existing expiry automatically
                        $this->grantPremium($targetChild, $plan);

                        $targetChild->notify(new \App\Notifications\SubscriptionConfirmedNotification(
                            $targetChild,
                            $plan,
                            $payment->amount,
                            $payment->currency,
                            $targetChild->premium_ends_at,
                            $payment->transaction_id
                        ));

                        // Also notify the parent if different user made payment
                        if ($targetChild->parent_id && $targetChild->parent_id !== $payment->user_id) {
                            $parent = User::find($targetChild->parent_id);
                            if ($parent) {
                                $parent->notify(new \App\Notifications\SubscriptionConfirmedNotification(
                                    $targetChild,
                                    $plan,
                                    $payment->amount,
                                    $payment->currency,
                                    $targetChild->premium_ends_at,
                                    $payment->transaction_id
                                ));
                            }
                        } elseif ($wasRenewal && $targetChild->parent_id) {
                            // Parent paid — still notify for renewals
                            $parent = User::find($targetChild->parent_id);
                            if ($parent && $parent->id !== $payment->user_id) {
                                $parent->notify(new \App\Notifications\SubscriptionConfirmedNotification(
                                    $targetChild,
                                    $plan,
                                    $payment->amount,
                                    $payment->currency,
                                    $targetChild->premium_ends_at,
                                    $payment->transaction_id
                                ));
                            }
                        }
                    }

                    return redirect()->route('premium.success')->with('success', 'Premium subscription activated!');
                }
            }

            $payment->update([
                'status'            => 'failed',
                'gateway_reference' => $request->reference,
            ]);

            return redirect()->route('dashboard')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Paystack verification error for Premium: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'An error occurred during verification.');
        }
    }

    public function success()
    {
        return view('premium.success');
    }

    /**
     * Toggle auto-renewal for the authenticated user's premium subscription
     */
    public function toggleAutoRenewal(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('Child')) {
            $targetUser = $user;
        } elseif ($user->hasRole('Parent')) {
            $childId    = $request->input('child_id');
            $targetUser = User::find($childId);

            if (!$targetUser || $targetUser->parent_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Only parents and children can manage auto-renewal'], 403);
        }

        // Allow toggling regardless of subscription status so parent can pre-configure preference
        $targetUser->auto_renewal_enabled = !$targetUser->auto_renewal_enabled;
        $targetUser->save();

        $status = $targetUser->auto_renewal_enabled ? 'enabled' : 'disabled';

        return response()->json([
            'success'              => true,
            'message'              => "Auto-renewal has been {$status} for {$targetUser->first_name}.",
            'auto_renewal_enabled' => $targetUser->auto_renewal_enabled,
        ]);
    }

    private function grantPremium(User $child, string $plan): void
    {
        $child->premium_status = 'active';
        $child->premium_plan   = $plan;

        // Stack new subscription from the later of: existing expiry or now
        // This ensures renewals never start simultaneously with an active sub
        $baseDate = ($child->premium_ends_at && $child->premium_ends_at->isFuture())
            ? $child->premium_ends_at->copy()
            : now();

        $child->premium_ends_at = match ($plan) {
            'monthly'  => $baseDate->addMonth(),
            'termly'   => $baseDate->addMonths(4),
            'annually' => $baseDate->addYear(),
            default    => $baseDate->addMonth(),
        };

        $child->save();
    }
}
