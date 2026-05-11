<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $plan;
    protected $amount;
    protected $currency;
    protected $expiryDate;
    protected $transactionId;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $plan, float $amount, string $currency, $expiryDate, string $transactionId)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->expiryDate = $expiryDate;
        $this->transactionId = $transactionId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $planName = ucfirst($this->plan);
        $expiryFormatted = $this->expiryDate->format('F j, Y');

        return (new MailMessage)
            ->subject('✅ Premium Subscription Activated Successfully')
            ->greeting("Congratulations {$this->user->first_name}!")
            ->line("Your **{$planName}** premium subscription has been successfully activated!")
            ->line(' ')
            ->line('**Subscription Details:**')
            ->line("📋 Plan: {$planName}")
            ->line("💰 Amount: {$this->currency} " . number_format($this->amount, 2))
            ->line("📅 Expires: {$expiryFormatted}")
            ->line("🔑 Transaction ID: {$this->transactionId}")
            ->line(' ')
            ->line('**What You Can Now Access:**')
            ->line('✨ All premium rolling programmes')
            ->line('✨ Exclusive mentorship support')
            ->line('✨ Priority access to resources')
            ->line('✨ Certificate of completion')
            ->line('✨ Premium community features')
            ->line(' ')
            ->line('**Auto-Renewal Enabled**')
            ->line('Your subscription will automatically renew before expiration. You can disable auto-renewal anytime from your dashboard.')
            ->action('View Premium Dashboard', route('premium.subscribe'))
            ->line(' ')
            ->line('Welcome to the premium community! If you have any questions, our support team is here to help.')
            ->salutation('Best regards, Young Productive Muslim Mentoring Hub');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_confirmed',
            'title' => '✅ Premium Subscription Activated',
            'message' => "Your {$this->plan} premium subscription has been successfully activated! Expires on {$this->expiryDate->format('M d, Y')}.",
            'plan' => $this->plan,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'expiry_date' => $this->expiryDate->format('M d, Y H:i A'),
            'transaction_id' => $this->transactionId,
            'action_url' => route('premium.subscribe'),
            'action_label' => 'View Dashboard',
            'icon' => 'check-circle',
            'color' => 'success',
        ];
    }
}
