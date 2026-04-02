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
        Schema::create('program_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->enum('content_type', ['video', 'pdf']);
            $table->string('youtube_url')->nullable();
            $table->string('file_path')->nullable();

            // For Rolling Programs (age-relative scheduling)
            $table->integer('target_age')->nullable(); // e.g. 9
            $table->integer('week_number')->nullable(); // e.g. 2
            $table->integer('day_number')->nullable();  // e.g. 3
            $table->time('time_of_day')->nullable();    // e.g. 17:00:00

            // For Scheduled Programs (absolute scheduling)
            $table->dateTime('publish_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_contents');
    }
};
