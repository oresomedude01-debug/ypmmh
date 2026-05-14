<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MenteeCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $child;

    /**
     * Create a new notification instance.
     */
    public function __construct($child)
    {
        $this->child = $child;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Mentee Added Successfully')
            ->line('You have successfully added ' . $this->child->first_name . ' ' . $this->child->last_name . ' as a mentee.')
            ->line('They can now log in using their email: ' . $this->child->email)
            ->action('View Dashboard', route('parent.dashboard'))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'mentee_created',
            'message' => 'New Mentee account created for ' . $this->child->first_name . ' ' . $this->child->last_name . '.',
            'child_id' => $this->child->id,
            'child_name' => $this->child->first_name . ' ' . $this->child->last_name,
        ];
    }
}
