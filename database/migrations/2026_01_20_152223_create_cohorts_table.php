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
        Schema::create('cohorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('name');

            // Mentor assigned to this cohort (optional override)
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();

            // Age rules (mainly for rolling programs)
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();

            // Used mostly for scheduled programs
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohorts');
    }
};
