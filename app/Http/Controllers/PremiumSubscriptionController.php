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
            'premium_currency'
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
     * Handle checkout creation
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,termly,annually',
            'child_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        $targetChild = User::findOrFail($request->child_id);

        if ($user->hasRole('Child') && $user->id !== $targetChild->id) {
            abort(403);
        }

        if ($user->hasRole('Parent') && $targetChild->parent_id !== $user->id) {
            abort(403);
        }

        $plan = $request->plan;
        $priceKey = 'premium_price_' . $plan;
        
        $price = Setting::get($priceKey, 0);

        // Premium always requires payment - no free tier
        if ($price <= 0) {
            return back()->with('error', 'Premium pricing is not configured. Please contact support.');
        }

        // Create pending payment record
        $payment = Payment::create([
            'transaction_id' => 'PRM-' . strtoupper(Str::random(12)),
            'user_id' => $user->id,
            'child_id' => $targetChild->id,
            // we have program_id which is nullable. We'll leave it null for global premium
            'amount' => $price,
            'currency' => Setting::get('premium_currency', 'NGN'),
            'status' => 'pending',
            'payment_method' => 'paystack',
        ]);

        // Attach plan to session since payments table might not have 'plan' column
        session(['payment_premium_plan_' . $payment->id => $plan]);

        // Get Paystack public key from settings
        $paystackPublicKey = Setting::get('paystack_public_key', '');

        if (!$paystackPublicKey) {
            return back()->with('error', 'Payment gateway is not fully configured.');
        }

        return view('premium.checkout', compact('payment', 'paystackPublicKey', 'user', 'targetChild', 'plan'));
    }

    /**
     * Verify Paystack payment
     */
    public function verify(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'reference' => 'required|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $paystackSecretKey = Setting::get('paystack_secret_key', '');

        try {
            // Verify payment
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paystackSecretKey,
                'Content-Type' => 'application/json',
            ])->get('https://api.paystack.co/transaction/verify/' . $request->reference);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                if ($result['data']['status'] === 'success') {
                    // Payment successful
                    $payment->update([
                        'status' => 'success',
                        'gateway_reference' => $result['data']['reference'],
                        'paid_at' => now(),
                    ]);

                    $plan = session('payment_premium_plan_' . $payment->id, 'monthly');
                    $targetChild = User::find($payment->child_id);

                    if ($targetChild) {
                        $previousExpiryDate = $targetChild->premium_ends_at && $targetChild->premium_ends_at->isFuture() 
                            ? $targetChild->premium_ends_at 
                            : null;
                        
                        $this->grantPremium($targetChild, $plan);
                        
                        // Send confirmation notification
                        $targetChild->notify(new \App\Notifications\SubscriptionConfirmedNotification(
                            $targetChild,
                            $plan,
                            $payment->amount,
                            $payment->currency,
                            $targetChild->premium_ends_at,
                            $payment->transaction_id
                        ));
                        
                        // If this was a renewal, also notify parent
                        if ($previousExpiryDate && $targetChild->parent_id) {
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
                        }
                    }

                    return redirect()->route('premium.success')->with('success', 'Premium subscription successful!');
                }
            }

            // Payment failed
            $payment->update([
                'status' => 'failed',
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
            $childId = $request->input('child_id');
            $targetUser = User::find($childId);
            
            if (!$targetUser || $targetUser->parent_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Only parents and children can manage auto-renewal'], 403);
        }

        if ($targetUser->premium_status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Only active subscriptions can enable auto-renewal'], 400);
        }

        $targetUser->auto_renewal_enabled = !$targetUser->auto_renewal_enabled;
        $targetUser->save();

        $status = $targetUser->auto_renewal_enabled ? 'enabled' : 'disabled';
        
        return response()->json([
            'success' => true,
            'message' => "Auto-renewal has been {$status}",
            'auto_renewal_enabled' => $targetUser->auto_renewal_enabled,
        ]);
    }

    private function grantPremium(User $child, string $plan)
    {
        $child->premium_status = 'active';
        $child->premium_plan = $plan;
        $child->auto_renewal_enabled = true; // Enable auto-renewal by default
        
        $currentEndsAt = ($child->premium_ends_at && $child->premium_ends_at->isFuture()) 
            ? $child->premium_ends_at 
            : now();

        if ($plan === 'monthly') {
            $child->premium_ends_at = $currentEndsAt->addMonth();
        } elseif ($plan === 'termly') {
            $child->premium_ends_at = $currentEndsAt->addMonths(4);
        } elseif ($plan === 'annually') {
            $child->premium_ends_at = $currentEndsAt->addYear();
        }

        $child->save();
    }
}
