<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Push Notification Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Web Push Protocol (RFC 8030) push notifications.
    | This uses VAPID (Voluntary Application Server Identification) for signing.
    |
    */

    // VAPID Public Key (share with clients)
    'vapid_public_key' => env('VAPID_PUBLIC_KEY'),

    // VAPID Private Key (keep secure)
    'vapid_private_key' => env('VAPID_PRIVATE_KEY'),

    // Subject for VAPID (usually mailto: email)
    'vapid_subject' => env('VAPID_SUBJECT', 'mailto:admin@ypmmh.local'),

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */

    // Enable/disable push notifications globally
    'enabled' => env('PUSH_NOTIFICATIONS_ENABLED', true),

    // Time to live for push messages (in seconds)
    'ttl' => env('PUSH_TTL', 86400), // 24 hours

    // Batch processing
    'batch_size' => env('PUSH_BATCH_SIZE', 100),
    'batch_delay' => env('PUSH_BATCH_DELAY', 1000), // milliseconds

    /*
    |--------------------------------------------------------------------------
    | Cleanup
    |--------------------------------------------------------------------------
    */

    // Automatically clean up failed subscriptions after N days
    'cleanup_failed_after_days' => env('PUSH_CLEANUP_AFTER_DAYS', 30),

    // Cleanup old push logs after N days
    'cleanup_logs_after_days' => env('PUSH_LOGS_CLEANUP_AFTER_DAYS', 90),

    /*
    |--------------------------------------------------------------------------
    | Notification Type Configuration
    |--------------------------------------------------------------------------
    */

    'notification_types' => [
        'content_update' => [
            'label' => 'Content & Lessons',
            'icon' => 'fas fa-book',
            'color' => 'blue',
            'enabled' => true,
        ],
        'message' => [
            'label' => 'Messages & Chat',
            'icon' => 'fas fa-comments',
            'color' => 'green',
            'enabled' => true,
        ],
        'achievement' => [
            'label' => 'Achievements & XP',
            'icon' => 'fas fa-trophy',
            'color' => 'yellow',
            'enabled' => true,
        ],
        'program_update' => [
            'label' => 'Program Updates',
            'icon' => 'fas fa-list',
            'color' => 'purple',
            'enabled' => true,
        ],
        'admin_alert' => [
            'label' => 'Admin Alerts',
            'icon' => 'fas fa-exclamation-triangle',
            'color' => 'red',
            'enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Role-Based Notification Routing
    |--------------------------------------------------------------------------
    */

    'role_routes' => [
        'Admin' => [
            'admin_alert',
            'report',
            'birthday',
        ],
        'Mentor' => [
            'program_update',
            'message',
            'admin_alert',
        ],
        'Parent' => [
            'content_update',
            'achievement',
            'message',
            'enrollment_request',
            'lesson_completion',
        ],
        'Child' => [
            'content_update',
            'achievement',
            'message',
            'program_update',
            'chat_suspension',
        ],
    ],
];
