<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // Mentor
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@example.com'],
            [
                'first_name' => 'Ustadh',
                'last_name' => 'Mentor',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $mentor->assignRole('Mentor');

        // Parent
        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'first_name' => 'Parent',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'address' => 'Lagos, Nigeria',
                'phone_number' => '+2348000000000',
                'email_verified_at' => now(),
            ]
        );
        $parent->assignRole('Parent');

        // Children for the Parent
        $child1 = User::firstOrCreate(
            ['email' => 'child1@example.com'], // Optional email for children
            [
                'parent_id' => $parent->id,
                'first_name' => 'Ali',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'date_of_birth' => '2015-01-01', // 10 years old approx
                'gender' => 'male',
                'unique_number' => 'YPMMH-001',
                'xp_points' => 50,
            ]
        );
        $child1->assignRole('Child');

        $child2 = User::firstOrCreate(
            ['email' => 'child2@example.com'],
            [
                'parent_id' => $parent->id,
                'first_name' => 'Amina',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'date_of_birth' => '2016-05-15', // 9 years old approx
                'gender' => 'female',
                'unique_number' => 'YPMMH-002',
                'xp_points' => 120,
            ]
        );
        $child2->assignRole('Child');
    }
}
