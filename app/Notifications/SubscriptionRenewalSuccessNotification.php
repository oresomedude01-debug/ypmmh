<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewalSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $plan;
    protected $amount;
    protected $currency;
    protected $newExpiryDate;
    protected $previousExpiryDate;
    protected $transactionId;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $plan, float $amount, string $currency, $newExpiryDate, $previousExpiryDate, string $transactionId)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->newExpiryDate = $newExpiryDate;
        $this->previousExpiryDate = $previousExpiryDate;
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
        $newExpiryFormatted = $this->newExpiryDate->format('F j, Y');
        $previousExpiryFormatted = $this->previousExpiryDate->format('F j, Y');

        return (new MailMessage)
            ->subject('🎉 Premium Subscription Renewed Successfully')
            ->greeting("Hello {$this->user->first_name}!")
            ->line("Your **{$planName}** premium subscription has been successfully renewed!")
            ->line(' ')
            ->line('**Renewal Details:**')
            ->line("📋 Plan: {$planName}")
            ->line("💰 Amount: {$this->currency} " . number_format($this->amount, 2))
            ->line("📅 Previous Expiry: {$previousExpiryFormatted}")
            ->line("✅ New Expiry: {$newExpiryFormatted}")
            ->line("🔑 Transaction ID: {$this->transactionId}")
            ->line(' ')
            ->line('**Your Premium Benefits Continue:**')
            ->line('✨ Uninterrupted access to all programmes')
            ->line('✨ Continued mentorship support')
            ->line('✨ Exclusive resources and content')
            ->line('✨ Community participation')
            ->line(' ')
            ->line('**Auto-Renewal Still Active**')
            ->line('Your subscription will continue to renew automatically. You can change this setting anytime.')
            ->action('View Premium Dashboard', route('premium.subscribe'))
            ->line(' ')
            ->line('Thank you for staying with us! Your continued commitment to growth and development is appreciated.')
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
            'type' => 'subscription_renewal_success',
            'title' => '🎉 Premium Subscription Renewed',
            'message' => "Your {$this->plan} subscription has been renewed successfully! New expiry: {$this->newExpiryDate->format('M d, Y')}",
            'plan' => $this->plan,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'previous_expiry_date' => $this->previousExpiryDate->format('M d, Y H:i A'),
            'new_expiry_date' => $this->newExpiryDate->format('M d, Y H:i A'),
            'transaction_id' => $this->transactionId,
            'action_url' => route('premium.subscribe'),
            'action_label' => 'View Dashboard',
            'icon' => 'star',
            'color' => 'success',
        ];
    }
}
