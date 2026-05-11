<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $expiryDate = $this->user->premium_ends_at?->format('F j, Y') ?? 'Unknown Date';

        $message = (new MailMessage)
            ->subject('💔 Your Premium Subscription Has Expired')
            ->greeting("Hello {$this->user->first_name},")
            ->line("Your **{$planName}** premium subscription has expired as of **{$expiryDate}**.")
            ->line(' ')
            ->line('You are now limited to the free features of our platform. To regain access to exclusive programs, content, and mentorship, please renew your subscription.')
            ->action('Renew Premium Subscription', route('premium.subscribe'))
            ->line(' ')
            ->line('**What You\'ll Get:**')
            ->line('✅ Access to all premium programs')
            ->line('✅ Priority mentorship support')
            ->line('✅ Exclusive content and resources')
            ->line('✅ Certificate of completion')
            ->line(' ')
            ->line('If you have any questions or need assistance, our support team is here to help.')
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
            'type' => 'subscription_expired',
            'title' => 'Premium Subscription Expired',
            'message' => "Your {$this->user->premium_plan} premium subscription has expired. Click to renew.",
            'expired_date' => $this->user->premium_ends_at?->format('M d, Y H:i A'),
            'action_url' => route('premium.subscribe'),
            'action_label' => 'Renew Subscription',
            'icon' => 'exclamation-circle',
            'color' => 'danger',
        ];
    }
}
