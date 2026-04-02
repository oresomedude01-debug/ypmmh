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
            $table->string('premium_status')->default('none'); // none, trial, active, expired
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('premium_ends_at')->nullable();
            $table->string('premium_plan')->nullable(); // monthly, termly, annually
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['premium_status', 'trial_ends_at', 'premium_ends_at', 'premium_plan']);
        });
    }
};
