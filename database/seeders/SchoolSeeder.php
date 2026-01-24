<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test school
        $school = School::create([
            'name' => 'Demo High School',
            'code' => 'DHS',
            'subdomain' => 'demo',
            'status' => 'active',
        ]);

        // Create super admin (you)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@sms.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Create school admin
        User::create([
            'name' => 'School Admin',
            'email' => 'schooladmin@demo.com',
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create test classes
        $classes = [
            ['name' => '10', 'section' => 'A'],
            ['name' => '10', 'section' => 'B'],
            ['name' => '9', 'section' => 'A'],
            ['name' => '9', 'section' => 'B'],
        ];

        foreach ($classes as $classData) {
            SchoolClass::create([
                'school_id' => $school->id,
                'name' => $classData['name'],
                'section' => $classData['section'],
            ]);
        }

        $this->command->info('Demo school and users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: admin@sms.com / password');
        $this->command->info('School Admin: schooladmin@demo.com / password');
    }
}
