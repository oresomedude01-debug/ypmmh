<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SubscriptionExpiringNotification;
use App\Notifications\SubscriptionExpiredNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPremiumSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring and expired premium subscriptions and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('🔍 Checking premium subscriptions...');

        // Check for subscriptions expiring in 3 days
        $expiringIn3Days = User::where('premium_status', 'active')
            ->whereBetween('premium_ends_at', [now(), now()->addDays(3)])
            ->get();

        foreach ($expiringIn3Days as $user) {
            $daysRemaining = now()->diffInDays($user->premium_ends_at);
            
            // Only send if we haven't sent in the last 24 hours
            if (!$user->last_premium_notification_sent_at || $user->last_premium_notification_sent_at->addHours(24)->isPast()) {
                $user->notify(new SubscriptionExpiringNotification($user, $daysRemaining));
                $user->update(['last_premium_notification_sent_at' => now()]);
                $this->line("✉️  Sent expiring notification to: {$user->email}");
            }
        }

        // Check for expired subscriptions
        $expired = User::where('premium_status', 'active')
            ->where('premium_ends_at', '<', now())
            ->get();

        foreach ($expired as $user) {
            $user->notify(new SubscriptionExpiredNotification($user));
            
            // Mark as expired
            $user->update([
                'premium_status' => 'expired',
                'auto_renewal_enabled' => false, // Disable auto-renewal on expiry
            ]);
            
            $this->line("❌ Marked as expired and notified: {$user->email}");
        }

        // Check for auto-renewal
        $autoRenewUsers = User::where('premium_status', 'expired')
            ->where('auto_renewal_enabled', true)
            ->where('premium_ends_at', '<', now()->subDays(1)) // Give 24 hours before renewal attempt
            ->limit(10) // Process max 10 per run to avoid overload
            ->get();

        foreach ($autoRenewUsers as $user) {
            $this->attemptAutoRenewal($user);
        }

        $this->info('✅ Premium subscription check completed!');
    }

    /**
     * Attempt to auto-renew a user's subscription
     */
    private function attemptAutoRenewal(User $user)
    {
        try {
            // Get the user's plan
            $plan = $user->premium_plan ?? 'monthly';
            
            // Get pricing
            $priceKey = 'premium_price_' . $plan;
            $price = \App\Models\Setting::get($priceKey, 0);

            if ($price <= 0) {
                Log::warning("Auto-renewal failed for {$user->email}: No pricing configured");
                return;
            }

            $previousExpiryDate = $user->premium_ends_at;

            // Create a payment record for auto-renewal
            $payment = \App\Models\Payment::create([
                'transaction_id' => 'AUTO-RENEWAL-' . strtoupper(\Illuminate\Support\Str::random(12)),
                'user_id' => $user->id,
                'child_id' => $user->id,
                'amount' => $price,
                'currency' => \App\Models\Setting::get('premium_currency', 'NGN'),
                'status' => 'success',
                'payment_method' => 'paystack_recurring',
                'description' => 'Auto-renewal of ' . $plan . ' premium subscription',
                'paid_at' => now(),
            ]);

            // Grant premium (renews subscription)
            $this->grantPremiumRenewal($user, $plan);
            
            // Send renewal success notification to user
            $user->notify(new \App\Notifications\SubscriptionRenewalSuccessNotification(
                $user,
                $plan,
                $price,
                \App\Models\Setting::get('premium_currency', 'NGN'),
                $user->premium_ends_at,
                $previousExpiryDate,
                $payment->transaction_id
            ));
            
            // If user is a child, also notify parent
            if ($user->hasRole('Child') && $user->parent_id) {
                $parent = User::find($user->parent_id);
                if ($parent) {
                    $parent->notify(new \App\Notifications\SubscriptionRenewalSuccessNotification(
                        $user,
                        $plan,
                        $price,
                        \App\Models\Setting::get('premium_currency', 'NGN'),
                        $user->premium_ends_at,
                        $previousExpiryDate,
                        $payment->transaction_id
                    ));
                }
            }
            
            $this->line("✅ Auto-renewal successful for: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Auto-renewal error for {$user->email}: " . $e->getMessage());
            $this->error("Error renewing {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Grant premium renewal (extends subscription)
     */
    private function grantPremiumRenewal(User $user, string $plan)
    {
        $currentEndsAt = $user->premium_ends_at ?? now();

        if ($plan === 'monthly') {
            $user->premium_ends_at = $currentEndsAt->addMonth();
        } elseif ($plan === 'termly') {
            $user->premium_ends_at = $currentEndsAt->addMonths(4);
        } elseif ($plan === 'annually') {
            $user->premium_ends_at = $currentEndsAt->addYear();
        }

        $user->premium_status = 'active';
        $user->save();
    }
}
