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
            // Add auto-renewal column if it doesn't exist
            if (!Schema::hasColumn('users', 'auto_renewal_enabled')) {
                $table->boolean('auto_renewal_enabled')->default(false)->after('premium_plan');
            }
            
            // Add last_premium_notification_sent_at for tracking reminders
            if (!Schema::hasColumn('users', 'last_premium_notification_sent_at')) {
                $table->timestamp('last_premium_notification_sent_at')->nullable()->after('auto_renewal_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['auto_renewal_enabled', 'last_premium_notification_sent_at']);
        });
    }
};
