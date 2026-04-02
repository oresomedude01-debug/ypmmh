<?php

namespace App\Services;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use App\Models\PushSubscription;
use App\Models\PushNotificationLog;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private ?WebPush $webPush = null;
    private array $vapidKeys;

    public function __construct()
    {
        $this->vapidKeys = [
            'publicKey' => config('app.vapid_public_key'),
            'privateKey' => config('app.vapid_private_key'),
        ];
    }

    /**
     * Get WebPush instance (lazy-loaded)
     */
    private function getWebPush(): WebPush
    {
        if ($this->webPush === null) {
            $this->webPush = new WebPush($this->vapidKeys);
            // Optional: Set the subject (mailto usually)
            $this->webPush->setAutomaticPadding(true);
        }
        return $this->webPush;
    }

    /**
     * Send push notification to a specific subscription
     */
    public function sendToSubscription(
        PushSubscription $subscription,
        string $title,
        string $body,
        array $options = []
    ): bool {
        try {
            // Check if subscription can receive notifications
            $type = $options['notification_type'] ?? 'general';
            if (!$subscription->canReceiveNotification($type)) {
                Log::debug('Subscription cannot receive notification type: ' . $type, [
                    'subscription_id' => $subscription->id,
                ]);
                return false;
            }

            $payload = [
                'title' => $title,
                'body' => $body,
                'icon' => $options['icon'] ?? asset('icons/icon-192x192.png'),
                'badge' => $options['badge'] ?? asset('icons/badge-72x72.png'),
                'tag' => $options['tag'] ?? 'notification',
                'data' => $options['data'] ?? [],
                'actions' => $options['actions'] ?? [
                    [
                        'action' => 'open',
                        'title' => 'Open',
                        'icon' => asset('icons/icon-96x96.png'),
                    ],
                ],
                'requireInteraction' => $options['requireInteraction'] ?? false,
            ];

            // Create subscription object for web-push library
            $subscription_obj = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'publicKey' => $subscription->public_key,
                'authToken' => $subscription->auth_token,
                'p256dh' => $subscription->p256dh,
            ]);

            // Send notification
            $report = $this->getWebPush()->sendOneNotification(
                $subscription_obj,
                json_encode($payload),
                true // Flush immediately
            );

            if ($report->isSuccess()) {
                $subscription->markAsUsed();
                Log::info('Push notification sent successfully', [
                    'subscription_id' => $subscription->id,
                ]);
                return true;
            } else {
                $reason = $report->getReason();
                $subscription->markFailed($reason);
                Log::warning('Push notification failed', [
                    'subscription_id' => $subscription->id,
                    'reason' => $reason,
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error sending push notification', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
            $subscription->markFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to a user to all their subscriptions
     */
    public function sendToUser(
        User $user,
        string $title,
        string $body,
        array $options = []
    ): Collection {
        $results = collect();

        // Get active subscriptions
        $subscriptions = $user->pushSubscriptions()
            ->where('is_active', true)
            ->get();

        foreach ($subscriptions as $subscription) {
            $success = $this->sendToSubscription($subscription, $title, $body, $options);
            $results->push([
                'subscription_id' => $subscription->id,
                'success' => $success,
            ]);
        }

        return $results;
    }

    /**
     * Send push notification to multiple users (batch)
     */
    public function sendToUsers(
        Collection $users,
        string $title,
        string $body,
        array $options = []
    ): array {
        $batchId = uniqid('batch_');
        $results = [];

        foreach ($users as $user) {
            $userResults = $this->sendToUser($user, $title, $body, $options);
            $results[$user->id] = $userResults;
        }

        return [
            'batch_id' => $batchId,
            'results' => $results,
        ];
    }

    /**
     * Send to users by role (e.g., 'Admin', 'Child', 'Parent', 'Mentor')
     */
    public function sendToUsersByRole(
        string $role,
        string $title,
        string $body,
        array $options = []
    ): array {
        $users = User::role($role)->with('pushSubscriptions')->get();
        return $this->sendToUsers($users, $title, $body, $options);
    }

    /**
     * Send to users in a specific program
     */
    public function sendToProgramUsers(
        int $programId,
        string $title,
        string $body,
        string $excludeRole = null,
        array $options = []
    ): array {
        $query = User::whereHas('enrollments', function ($q) use ($programId) {
            $q->where('program_id', $programId);
        })->with('pushSubscriptions');

        if ($excludeRole) {
            $query->whereDoesntHave('roles', function ($q) use ($excludeRole) {
                $q->where('name', $excludeRole);
            });
        }

        $users = $query->get();
        return $this->sendToUsers($users, $title, $body, $options);
    }

    /**
     * Log notification sent
     */
    public function logNotification(
        User $user,
        PushSubscription $subscription = null,
        string $notificationType,
        string $title,
        string $body,
        array $data = [],
        string $status = 'pending'
    ): PushNotificationLog {
        return PushNotificationLog::create([
            'user_id' => $user->id,
            'push_subscription_id' => $subscription?->id,
            'notification_type' => $notificationType,
            'title' => $title,
            'body' => $body,
            'icon' => $data['icon'] ?? null,
            'badge' => $data['badge'] ?? null,
            'tag' => $data['tag'] ?? null,
            'data' => $data,
            'status' => $status,
            'user_role' => $user->getRoleNames()->first(),
            'targeting_data' => [
                'device_type' => $subscription?->device_type,
                'browser' => $subscription?->browser,
            ],
        ]);
    }

    /**
     * Get VAPID public key for client-side registration
     */
    public function getVapidPublicKey(): string
    {
        return $this->vapidKeys['publicKey'];
    }

    /**
     * Test send a notification (for debugging)
     */
    public function testSend(PushSubscription $subscription): bool
    {
        return $this->sendToSubscription($subscription, 'Test Notification', 'If you see this, push notifications are working! 🎉', [
            'notification_type' => 'test',
            'tag' => 'test-notification',
            'data' => [
                'test' => true,
            ],
        ]);
    }

    /**
     * Clean up old failed subscriptions
     */
    public function cleanupFailedSubscriptions(): int
    {
        return PushSubscription::where('is_active', false)
            ->where('updated_at', '<', now()->subDays(30))
            ->delete();
    }
}
