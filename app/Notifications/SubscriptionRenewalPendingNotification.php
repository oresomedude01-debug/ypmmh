<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewalPendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Payment $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
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
        $planName = ucfirst($this->user->premium_plan ?? 'Premium');
        $amount = $this->payment->amount;
        $currency = $this->payment->currency;

        return (new MailMessage)
            ->subject('🔄 Auto-Renewal Initiated for Your Premium Subscription')
            ->greeting("Hello {$this->user->first_name},")
            ->line("Your auto-renewal has been initiated for your **{$planName}** subscription.")
            ->line("**Amount:** {$currency} {$amount}")
            ->line("**Transaction ID:** {$this->payment->transaction_id}")
            ->line(' ')
            ->line('Your subscription will be renewed automatically. If you have any issues or need to modify your auto-renewal settings, please contact us immediately.')
            ->action('View Premium Dashboard', route('premium.subscribe'))
            ->line(' ')
            ->line('Thank you for your continued subscription!')
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
            'type' => 'subscription_renewal_pending',
            'title' => 'Auto-Renewal Initiated',
            'message' => "Your {$this->user->premium_plan} subscription is being renewed automatically.",
            'payment_id' => $this->payment->id,
            'transaction_id' => $this->payment->transaction_id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'action_url' => route('premium.subscribe'),
            'action_label' => 'View Details',
            'icon' => 'sync',
            'color' => 'info',
        ];
    }
}
