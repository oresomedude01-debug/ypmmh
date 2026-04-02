<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'endpoint',
        'public_key',
        'auth_token',
        'p256dh',
        'user_agent',
        'device_type',
        'browser',
        'ip_address',
        'is_active',
        'last_used_at',
        'failed_at',
        'failure_count',
        'notify_content_updates',
        'notify_messages',
        'notify_achievements',
        'notify_program_updates',
        'notify_admin_alerts',
        'quiet_hours_start',
        'quiet_hours_end',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'notify_content_updates' => 'boolean',
        'notify_messages' => 'boolean',
        'notify_achievements' => 'boolean',
        'notify_program_updates' => 'boolean',
        'notify_admin_alerts' => 'boolean',
        'last_used_at' => 'datetime',
        'failed_at' => 'datetime',
        'quiet_hours_start' => 'datetime:H:i',
        'quiet_hours_end' => 'datetime:H:i',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get logs for this subscription
     */
    public function logs()
    {
        return $this->hasMany(PushNotificationLog::class);
    }

    /**
     * Check if subscription is within quiet hours
     */
    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now();
        $start = $now->copy()->setTimeFromTimeString($this->quiet_hours_start->format('H:i'));
        $end = $now->copy()->setTimeFromTimeString($this->quiet_hours_end->format('H:i'));

        if ($start > $end) {
            // Quiet hours span midnight
            return $now >= $start || $now < $end;
        }

        return $now >= $start && $now < $end;
    }

    /**
     * Check if subscription can receive notifications of given type
     */
    public function canReceiveNotification(string $type): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->isInQuietHours()) {
            return false;
        }

        return match ($type) {
            'content_update', 'new_content', 'lesson' => $this->notify_content_updates,
            'message', 'chat' => $this->notify_messages,
            'achievement', 'xp' => $this->notify_achievements,
            'program_update' => $this->notify_program_updates,
            'admin_alert' => $this->notify_admin_alerts,
            default => true,
        };
    }

    /**
     * Mark subscription as failed
     */
    public function markFailed(string $reason = null): void
    {
        $this->increment('failure_count');
        $this->update([
            'failed_at' => now(),
            'is_active' => $this->failure_count < 10, // Disable after 10 failures
        ]);
    }

    /**
     * Mark subscription as used
     */
    public function markAsUsed(): void
    {
        $this->update([
            'last_used_at' => now(),
            'failure_count' => 0,
        ]);
    }
}
