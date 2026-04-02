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
        Schema::create('push_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('push_subscription_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Notification details
            $table->string('notification_type'); // e.g., 'new_content', 'message', 'achievement'
            $table->string('title');
            $table->text('body');
            $table->string('icon')->nullable();
            $table->string('badge')->nullable();
            $table->string('tag')->nullable(); // For grouping notifications
            $table->json('data')->nullable(); // Additional data (links, IDs, etc.)
            
            // Delivery status
            $table->string('status')->default('pending'); // pending, sent, failed, read, clicked
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->string('error_message')->nullable();
            
            // Targeting
            $table->string('user_role')->nullable();
            $table->json('targeting_data')->nullable();
            
            // Batch tracking
            $table->string('batch_id')->nullable(); // For grouping related notifications
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('notification_type');
            $table->index('status');
            $table->index('created_at');
            $table->index('batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notification_logs');
    }
};
