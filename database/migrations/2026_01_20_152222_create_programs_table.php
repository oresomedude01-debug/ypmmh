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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['rolling', 'scheduled']); // rolling = age-based, scheduled = cohort-based
            $table->text('description')->nullable();

            // Mentor responsible for this program
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();

            // For Rolling (Age-Progressive) Programs
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();

            // For Scheduled (Cohort) Programs
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
