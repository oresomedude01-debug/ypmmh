<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add first_name and last_name columns
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Populate first_name and last_name from existing name column
        DB::statement('UPDATE users SET first_name = SUBSTRING_INDEX(name, " ", 1), last_name = TRIM(SUBSTR(name, LOCATE(" ", name)))');

        // Drop the name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back name column
            $table->string('name')->after('id');
        });

        // Populate name from first_name and last_name
        DB::statement('UPDATE users SET name = CONCAT(first_name, " ", last_name)');

        // Drop first_name and last_name
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
