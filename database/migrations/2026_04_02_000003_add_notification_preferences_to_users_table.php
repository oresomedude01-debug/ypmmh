<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Global notification preferences
            $table->json('notification_preferences')->nullable()->after('premium_plan');
            // Example: {
            //   "push_enabled": true,
            //   "email_notifications": true,
            //   "in_app_notifications": true,
            //   "notification_frequency": "real-time" | "daily" | "weekly"
            // }
            
            $table->boolean('push_notifications_enabled')->default(true)->after('notification_preferences');
            $table->boolean('email_notifications_enabled')->default(true)->after('push_notifications_enabled');
            $table->timestamp('last_notification_read_at')->nullable()->after('email_notifications_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notification_preferences', 'push_notifications_enabled', 'email_notifications_enabled', 'last_notification_read_at']);
        });
    }
};
