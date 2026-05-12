<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\PushNotificationService;
use Illuminate\Support\Facades\Log;

class PushChannel
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toPush')) {
            return;
        }

        $data = $notification->toPush($notifiable);

        if (!$data) {
            return;
        }

        // Send push to all active subscriptions of the user
        $this->pushService->sendToUser(
            $notifiable,
            $data['title'],
            $data['body'],
            $data['options'] ?? []
        );
    }
}
