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

            // Create a payment record for auto-renewal
            $payment = \App\Models\Payment::create([
                'transaction_id' => 'AUTO-RENEWAL-' . strtoupper(\Illuminate\Support\Str::random(12)),
                'user_id' => $user->id,
                'child_id' => $user->id,
                'amount' => $price,
                'currency' => \App\Models\Setting::get('premium_currency', 'NGN'),
                'status' => 'pending',
                'payment_method' => 'paystack_recurring',
                'description' => 'Auto-renewal of ' . $plan . ' premium subscription',
            ]);

            // TODO: In a real implementation, you would call the payment gateway's recurring charge API
            // For now, we'll just mark it as requiring manual processing
            
            $user->notify(new \App\Notifications\SubscriptionRenewalPendingNotification($user, $payment));
            
            $this->line("⚙️  Auto-renewal initiated for: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Auto-renewal error for {$user->email}: " . $e->getMessage());
            $this->error("Error renewing {$user->email}: " . $e->getMessage());
        }
    }
}
