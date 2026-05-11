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
        // Only create the table if it doesn't already exist
        if (Schema::hasTable('push_subscriptions')) {
            return;
        }
        
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Web Push Protocol (subscription details)
            $table->string('endpoint'); // The push server endpoint
            $table->text('public_key'); // VAPID public key
            $table->text('auth_token'); // Authentication token for the subscription
            $table->text('p256dh'); // Encryption key
            
            // Subscription metadata
            $table->string('user_agent')->nullable();
            $table->string('device_type')->default('web'); // web, mobile, tablet
            $table->string('browser')->nullable();
            $table->ipAddress('ip_address')->nullable();
            
            // Subscription status
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->integer('failure_count')->default(0);
            
            // Notification preferences per subscription
            $table->boolean('notify_content_updates')->default(true);
            $table->boolean('notify_messages')->default(true);
            $table->boolean('notify_achievements')->default(true);
            $table->boolean('notify_program_updates')->default(true);
            $table->boolean('notify_admin_alerts')->default(true);
            
            // Quiet hours
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['user_id', 'endpoint']);
            $table->index('is_active');
            $table->index('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
