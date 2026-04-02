<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\PushNotificationLog;
use App\Services\PushNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPushNotifications extends Command
{
    protected $signature = 'push:send {--force : Force send even if already sent}';

    protected $description = 'Send push notifications based on database notifications and user subscriptions';

    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        parent::__construct();
        $this->pushService = $pushService;
    }

    public function handle()
    {
        $this->info('📨 Starting push notification distribution...');

        try {
            // Get unprocessed database notifications
            $notifications = $this->getUnprocessedNotifications();

            if ($notifications->isEmpty()) {
                $this->info('✓ No notifications to send');
                return 0;
            }

            $this->info("Found {$notifications->count()} notification(s) to send");

            $totalSent = 0;
            $totalFailed = 0;

            foreach ($notifications as $notification) {
                $this->info("\n📤 Processing notification: {$notification->id}");

                $result = $this->processNotification($notification);

                $totalSent += $result['sent'];
                $totalFailed += $result['failed'];
            }

            $this->info("\n✓ Push notification distribution complete!");
            $this->line("  • Sent: {$totalSent}");
            $this->line("  • Failed: {$totalFailed}");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            Log::error('Push notification send failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Get unprocessed notifications from database
     */
    private function getUnprocessedNotifications()
    {
        // Get recent notifications that haven't been processed yet
        return DB::table('notifications')
            ->where('created_at', '>=', now()->subHours(24))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('push_notification_logs')
                    ->whereRaw('push_notification_logs.data->"$.notification_id" = notifications.id');
            })
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
    }

    /**
     * Process a notification and send to appropriate users
     */
    private function processNotification($notification): array
    {
        try {
            $data = json_decode($notification->data, true) ?? [];
            $notifiedUser = User::find($notification->notifiable_id);

            if (!$notifiedUser) {
                $this->warn("  User not found: {$notification->notifiable_id}");
                return ['sent' => 0, 'failed' => 1];
            }

            // Map notification type to push notification details
            $pushDetails = $this->mapNotificationToPush($notification->type, $data, $notifiedUser);

            if (!$pushDetails) {
                return ['sent' => 0, 'failed' => 0]; // Skip silently
            }

            $this->line("  • Type: {$notification->type}");
            $this->line("  • User: {$notifiedUser->full_name} ({$notifiedUser->email})");
            $this->line("  • Title: {$pushDetails['title']}");

            // Send push notification
            $results = $this->pushService->sendToUser(
                $notifiedUser,
                $pushDetails['title'],
                $pushDetails['body'],
                [
                    'notification_type' => $notification->type,
                    'icon' => $pushDetails['icon'] ?? asset('icons/icon-192x192.png'),
                    'badge' => $pushDetails['badge'] ?? null,
                    'tag' => $pushDetails['tag'] ?? $notification->type,
                    'data' => array_merge($data, [
                        'notification_id' => $notification->id,
                        'notification_type' => $notification->type,
                        'url' => $pushDetails['url'] ?? '/dashboard',
                    ]),
                ]
            );

            // Log results
            $sent = $results->where('success', true)->count();
            $failed = $results->where('success', false)->count();

            if ($sent > 0) {
                $this->line("  ✓ Sent to {$sent} subscription(s)");
            }
            if ($failed > 0) {
                $this->warn("  ✗ Failed on {$failed} subscription(s)");
            }

            return ['sent' => $sent, 'failed' => $failed];
        } catch (\Exception $e) {
            $this->error("  Error processing notification: {$e->getMessage()}");
            Log::error('Error processing notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            return ['sent' => 0, 'failed' => 1];
        }
    }

    /**
     * Map database notification type to push notification details
     */
    private function mapNotificationToPush(string $type, array $data, User $user): ?array
    {
        // Don't send certain notification types as push
        $skipTypes = ['email_verified', 'password_reset'];
        if (in_array($type, $skipTypes)) {
            return null;
        }

        // Check if user wants push notifications of this type
        if (!$user->push_notifications_enabled) {
            $this->warn("  Push notifications disabled for user");
            return null;
        }

        // Map notification types
        return match ($type) {
            'App\Notifications\ProgramUpdateNotification' => $this->handleProgramUpdate($data, $user),
            'App\Notifications\BirthdayNotification' => $this->handleBirthdayNotification($data, $user),
            'App\Notifications\NewReportNotification' => $this->handleReportNotification($data, $user),
            'App\Notifications\Auth\VerifyEmail' => $this->handleVerifyEmail($data, $user),
            default => $this->handleGenericNotification($type, $data, $user),
        };
    }

    /**
     * Handle program update notifications
     */
    private function handleProgramUpdate(array $data, User $user): ?array
    {
        $type = $data['type'] ?? 'general';

        return match ($type) {
            'new_content' => [
                'title' => '📚 New Content',
                'body' => $data['message'] ?? 'New lesson has been added to your program',
                'icon' => asset('icons/content-icon.png'),
                'tag' => 'program-content',
                'url' => route('child.programs.show', $data['program_id'] ?? NULL),
            ],
            'message', 'chat' => [
                'title' => '💬 New Message',
                'body' => $data['message'] ?? 'You have a new message',
                'icon' => asset('icons/chat-icon.png'),
                'tag' => 'program-chat',
                'url' => route('child.programs.show', $data['program_id'] ?? NULL),
            ],
            'achievement' => [
                'title' => '🏆 Achievement Unlocked',
                'body' => $data['message'] ?? 'You\'ve unlocked a new achievement',
                'icon' => asset('icons/achievement-icon.png'),
                'tag' => 'achievement',
                'url' => route('child.dashboard'),
            ],
            'lesson_completion' => [
                'title' => '✅ Lesson Completed',
                'body' => $data['message'] ?? 'Your child completed a lesson',
                'icon' => asset('icons/lesson-icon.png'),
                'tag' => 'lesson-complete',
                'url' => route('parent.dashboard'),
            ],
            'enrollment_request' => [
                'title' => '📝 Program Interest',
                'body' => $data['message'] ?? 'Your child is interested in a program',
                'icon' => asset('icons/program-icon.png'),
                'tag' => 'enrollment',
                'url' => route('parent.dashboard'),
            ],
            'chat_suspension' => [
                'title' => '⚠️ Chat Status Updated',
                'body' => $data['message'] ?? 'Chat status has been updated',
                'icon' => asset('icons/warning-icon.png'),
                'tag' => 'chat-suspension',
                'url' => route('child.programs.show', $data['program_id'] ?? NULL),
            ],
            default => null,
        };
    }

    /**
     * Handle birthday notifications
     */
    private function handleBirthdayNotification(array $data, User $user): ?array
    {
        return [
            'title' => '🎂 Birthday Reminder',
            'body' => $data['message'] ?? 'A child\'s birthday is coming up soon',
            'icon' => asset('icons/birthday-icon.png'),
            'tag' => 'birthday',
            'url' => route('admin.users.index'),
        ];
    }

    /**
     * Handle report notifications
     */
    private function handleReportNotification(array $data, User $user): ?array
    {
        return [
            'title' => '⚠️ New Report',
            'body' => $data['message'] ?? 'A new report has been submitted',
            'icon' => asset('icons/report-icon.png'),
            'tag' => 'report',
            'url' => route('admin.dashboard'),
        ];
    }

    /**
     * Handle email verification notifications
     */
    private function handleVerifyEmail(array $data, User $user): ?array
    {
        return [
            'title' => '✉️ Verify Your Email',
            'body' => 'Please verify your email address to complete registration',
            'icon' => asset('icons/email-icon.png'),
            'tag' => 'verify-email',
            'url' => route('verification.notice'),
        ];
    }

    /**
     * Handle generic notifications
     */
    private function handleGenericNotification(string $type, array $data, User $user): ?array
    {
        return [
            'title' => 'YPMMH Notification',
            'body' => $data['message'] ?? 'You have a new notification',
            'icon' => asset('icons/icon-192x192.png'),
            'tag' => 'generic',
            'url' => route('child.dashboard'),
        ];
    }
}
