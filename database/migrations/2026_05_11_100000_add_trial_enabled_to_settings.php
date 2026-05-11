<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Seeds the premium_trial_enabled setting if it doesn't exist.
     */
    public function up(): void
    {
        // Seed the premium_trial_enabled key so it exists from the start
        DB::table('settings')->insertOrIgnore([
            'key'        => 'premium_trial_enabled',
            'value'      => '1',
            'group'      => 'premium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ensure trial_duration_days exists with a sensible default
        DB::table('settings')->insertOrIgnore([
            'key'        => 'trial_duration_days',
            'value'      => '14',
            'group'      => 'premium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['premium_trial_enabled'])->delete();
    }
};
