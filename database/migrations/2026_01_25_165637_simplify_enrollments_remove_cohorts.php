<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, handle the enrollments table
        Schema::table('enrollments', function (Blueprint $table) {
            // Check if column exists before dropping (to be safe)
            if (Schema::hasColumn('enrollments', 'cohort_id')) {
                $table->dropForeign(['cohort_id']);
                $table->dropColumn('cohort_id');
            }

            // Add program_id
            $table->foreignId('program_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Drop the cohorts table as it is no longer needed
        Schema::dropIfExists('cohort_user'); // Drop pivot if exists
        Schema::dropIfExists('cohorts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create cohorts table (simplified version just for rollback)
        Schema::create('cohorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('mentor_id')->nullable()->constrained('users');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
            $table->foreignId('cohort_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
