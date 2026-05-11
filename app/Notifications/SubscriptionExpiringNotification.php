<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, int $daysRemaining)
    {
        $this->user = $user;
        $this->daysRemaining = $daysRemaining;
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
        $expiryDate = $this->user->premium_ends_at->format('F j, Y');
        $planName = ucfirst($this->user->premium_plan ?? 'Premium');

        $message = (new MailMessage)
            ->subject('⏰ Your Premium Subscription is Expiring Soon')
            ->greeting("Hello {$this->user->first_name},")
            ->line("Your **{$planName}** subscription is expiring in **{$this->daysRemaining} day(s)**.")
            ->line("**Expiry Date:** {$expiryDate}")
            ->line(' ')
            ->line('To ensure uninterrupted access to exclusive programs and content, please renew your subscription.')
            ->action('Renew Premium Subscription', route('premium.subscribe'))
            ->line(' ')
            ->line('**Auto-Renewal:** If you have enabled auto-renewal, your subscription will be renewed automatically on the expiry date.')
            ->line(' ')
            ->line('If you have any questions, feel free to contact our support team.')
            ->salutation('Best regards, Young Productive Muslim Mentoring Hub');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiring',
            'title' => 'Premium Subscription Expiring Soon',
            'message' => "Your {$this->user->premium_plan} premium subscription expires in {$this->daysRemaining} day(s).",
            'expiry_date' => $this->user->premium_ends_at->format('M d, Y H:i A'),
            'days_remaining' => $this->daysRemaining,
            'action_url' => route('premium.subscribe'),
            'action_label' => 'Renew Now',
            'icon' => 'crown',
            'color' => 'warning',
        ];
    }
}
