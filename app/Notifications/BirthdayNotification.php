<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayNotification extends Notification
{
    use Queueable;

    protected $child;
    protected $daysUntil;

    /**
     * Create a new notification instance.
     */
    public function __construct($child, $daysUntil)
    {
        $this->child = $child;
        $this->daysUntil = $daysUntil;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'birthday',
            'child_id' => $this->child->id,
            'child_name' => $this->child->first_name . ' ' . $this->child->last_name,
            'message' => "{$this->child->first_name}'s birthday is in {$this->daysUntil} days!",
            'days_until' => $this->daysUntil,
        ];
    }
}
