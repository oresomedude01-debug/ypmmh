<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_contents', function (Blueprint $table) {
            $table->dropColumn('target_age');
        });

        // 1. Temporarily change to VARCHAR to allow arbitrary string updates
        DB::statement("ALTER TABLE program_contents MODIFY COLUMN content_type VARCHAR(255)");

        // 2. Map old values to new values
        DB::table('program_contents')->where('content_type', 'video')->update(['content_type' => 'video_pdf']);
        DB::table('program_contents')->where('content_type', 'pdf')->update(['content_type' => 'pdf_only']);

        // 3. Apply new ENUM definition
        DB::statement("ALTER TABLE program_contents MODIFY COLUMN content_type ENUM('video_pdf', 'pdf_only') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE program_contents MODIFY COLUMN content_type ENUM('video', 'pdf') NOT NULL");

        Schema::table('program_contents', function (Blueprint $table) {
            $table->integer('target_age')->nullable();
        });
    }
};
