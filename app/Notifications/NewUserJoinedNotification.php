<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewUserJoinedNotification extends Notification
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
        return (new MailMessage)
            ->subject('Growth Alert: New User Registered!')
            ->greeting('Salaam Admin!')
            ->line("A new user has just joined the platform: **{$this->user->full_name}**.")
            ->line("Email: {$this->user->email}")
            ->line("User ID: " . ($this->user->unique_number ?? $this->user->id))
            ->action('View User Directory', route('admin.users.index'))
            ->line('Building a productive generation, one registration at a time.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->full_name,
            'email' => $this->user->email,
            'type' => 'new_user_registration',
            'icon' => 'fas fa-user-plus',
            'message' => "👤 New User Registered: '{$this->user->full_name}' ({$this->user->email})"
        ];
    }
}
