<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgramUpdateNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     * 
     * $data should contain 'type', 'message', and optional 'program_id'
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', \App\Channels\PushChannel::class];
    }

    /**
     * Get the push representation of the notification.
     */
    public function toPush($notifiable)
    {
        $type = $this->data['type'] ?? 'system';
        $programId = $this->data['program_id'] ?? null;
        
        // Define target URL based on role
        $rolePrefix = 'child';
        if ($notifiable->hasRole('Admin')) $rolePrefix = 'admin';
        elseif ($notifiable->hasRole('Mentor')) $rolePrefix = 'mentor';
        
        $url = url("/{$rolePrefix}/communities" . ($programId ? "/{$programId}" : ""));

        return [
            'title' => $type === 'chat' ? '💬 New Community Message' : 'YPMMH Notification',
            'body' => $this->data['message'],
            'options' => [
                'notification_type' => $type,
                'tag' => $type === 'chat' ? 'chat-program-' . $programId : 'system-notif',
                'data' => [
                    'url' => $url,
                    'program_id' => $programId,
                    'notificationType' => $type
                ]
            ]
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->data['type'] ?? 'system',
            'message' => $this->data['message'],
            'program_id' => $this->data['program_id'] ?? null,
            'content_id' => $this->data['content_id'] ?? null,
        ];
    }
}

