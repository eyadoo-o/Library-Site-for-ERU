<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'student',
            'library_staff',
            'faculty_staff',
            'student_activity_coordinator'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
