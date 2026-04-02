<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $mentor = User::role('Mentor')->first();

        // If no mentor exists (unlikely if UserSeeder runs first), create a fallback or skip
        if (!$mentor) {
            // Alternatively, just grab the first user or skip
            return;
        }

        // Program 1: Islamic Studies Basic
        Program::firstOrCreate(
            ['name' => 'Islamic Studies Essentials'],
            [
                'type' => 'course',
                'description' => 'A fundamental course covering the basics of Aqeedah, Fiqh, and Seerah for young minds.',
                'price' => 5000.00,
                'is_free' => false,
                'mentor_id' => $mentor->id,
                'age_target' => 7, // Converted to integer
                'cohort_age_min' => 7,
                'cohort_age_max' => 10,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'status' => 'active', // 'published' -> 'active'
                'is_featured' => true,
                'thumbnail_path' => null, // Or a placeholder string if valid
            ]
        );

        // Program 2: Quran Recitation Circle
        Program::firstOrCreate(
            ['name' => 'Quran Recitation Circle'],
            [
                'type' => 'mentorship',
                'description' => 'Weekly live sessions to improve Tajweed and memorization.',
                'price' => 0.00,
                'is_free' => true,
                'mentor_id' => $mentor->id,
                'age_target' => 5, // Converted to integer
                'cohort_age_min' => 5,
                'cohort_age_max' => 18,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addYear(),
                'status' => 'active',
                'is_featured' => false,
            ]
        );

        // Program 3: Leadership for Youth
        Program::firstOrCreate(
            ['name' => 'Leadership for Youth'],
            [
                'type' => 'workshop',
                'description' => 'Developing the next generation of leaders with Islamic values.',
                'price' => 10000.00,
                'is_free' => false,
                'mentor_id' => $mentor->id,
                'age_target' => 12, // Converted to integer
                'cohort_age_min' => 12,
                'cohort_age_max' => 16,
                'start_date' => Carbon::now()->addWeek(),
                'end_date' => Carbon::now()->addMonths(1),
                'status' => 'active',
                'is_featured' => true,
            ]
        );
    }
}
