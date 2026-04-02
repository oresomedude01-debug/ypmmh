<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotificationLog extends Model
{
    protected $fillable = [
        'user_id',
        'push_subscription_id',
        'notification_type',
        'title',
        'body',
        'icon',
        'badge',
        'tag',
        'data',
        'status',
        'sent_at',
        'read_at',
        'clicked_at',
        'error_message',
        'user_role',
        'targeting_data',
        'batch_id',
    ];

    protected $casts = [
        'data' => 'json',
        'targeting_data' => 'json',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to PushSubscription
     */
    public function subscription()
    {
        return $this->belongsTo(PushSubscription::class, 'push_subscription_id');
    }

    /**
     * Get related notification if exists
     */
    public function notification()
    {
        $notificationId = $this->data['notification_id'] ?? null;
        if (!$notificationId) {
            return null;
        }

        return \DB::table('notifications')->find($notificationId);
    }

    /**
     * Mark as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark as clicked/interacted
     */
    public function markAsClicked(): void
    {
        $this->update([
            'status' => 'clicked',
            'clicked_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
