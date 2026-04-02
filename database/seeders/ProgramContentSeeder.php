<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramContent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProgramContentSeeder extends Seeder
{
    public function run(): void
    {
        $program1 = Program::where('name', 'Islamic Studies Essentials')->first();

        if ($program1) {
            // Week 1 Content
            ProgramContent::firstOrCreate(
                ['title' => 'Introduction to Aqeedah', 'program_id' => $program1->id],
                [
                    'content_type' => 'video_pdf', // Updated from 'video'
                    'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder
                    'week_number' => 1,
                    'day_number' => 1,
                    'is_active' => true,
                    'publish_at' => Carbon::now(),
                ]
            );

            ProgramContent::firstOrCreate(
                ['title' => 'The 5 Pillars of Islam', 'program_id' => $program1->id],
                [
                    'content_type' => 'pdf_only', // Updated from 'text'
                    'file_path' => null, // Assuming text content stored elsewhere or just title for now
                    'week_number' => 1,
                    'day_number' => 3,
                    'is_active' => true,
                    'publish_at' => Carbon::now()->addDays(2),
                ]
            );
        }

        $program2 = Program::where('name', 'Quran Recitation Circle')->first();

        if ($program2) {
            ProgramContent::firstOrCreate(
                ['title' => 'Surah Al-Fatiha Breakdown', 'program_id' => $program2->id],
                [
                    'content_type' => 'video_pdf',
                    'youtube_url' => 'https://www.youtube.com/watch?v=placeholder',
                    'week_number' => 1,
                    'day_number' => 1,
                    'is_active' => true,
                    'publish_at' => Carbon::now(),
                ]
            );
        }
    }
}
