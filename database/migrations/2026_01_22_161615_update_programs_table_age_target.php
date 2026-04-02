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
        Schema::table('programs', function (Blueprint $table) {
            // Change age_min to age_target for rolling programs
            $table->renameColumn('age_min', 'age_target');
            // Remove age_max as rolling programs only have a single year
            $table->dropColumn('age_max');
            
            // Add cohort-specific fields
            $table->integer('cohort_age_min')->nullable()->after('age_target');
            $table->integer('cohort_age_max')->nullable()->after('cohort_age_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->renameColumn('age_target', 'age_min');
            $table->integer('age_max')->nullable()->after('age_min');
            $table->dropColumn(['cohort_age_min', 'cohort_age_max']);
        });
    }
};
