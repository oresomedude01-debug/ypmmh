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
        Schema::table('programs', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('description');
            $table->boolean('is_free')->default(false)->after('price');
            $table->string('youtube_url')->nullable()->after('is_free');
            $table->string('thumbnail_path')->nullable()->after('youtube_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['price', 'is_free', 'youtube_url', 'thumbnail_path']);
        });
    }
};
