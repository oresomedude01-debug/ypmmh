<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix the enrollments.id column — restore AUTO_INCREMENT and PRIMARY KEY.
     *
     * The column lost its AUTO_INCREMENT attribute (likely during a prior
     * ALTER TABLE that modified the table structure), causing the
     * SQLSTATE HY000: 1364 error on insert.
     */
    public function up(): void
    {
        // Restore the id column as UNSIGNED BIGINT AUTO_INCREMENT
        DB::statement('ALTER TABLE `enrollments` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        // Remove AUTO_INCREMENT (rollback only — data is preserved)
        DB::statement('ALTER TABLE `enrollments` MODIFY `id` BIGINT UNSIGNED NOT NULL');
    }
};
