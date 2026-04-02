<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $mentor = Role::firstOrCreate(['name' => 'Mentor']);
        $parent = Role::firstOrCreate(['name' => 'Parent']);
        $child  = Role::firstOrCreate(['name' => 'Child']);

        $admin->givePermissionTo(Permission::all());

        $mentor->givePermissionTo([
            'manage cohorts',
            'upload content',
            'assign lessons',
            'review submissions',
            'track traits',
            'view reports',
        ]);

        $parent->givePermissionTo([
            'view reports',
        ]);

        $child->givePermissionTo([
            'track traits',
        ]);
    }
}
