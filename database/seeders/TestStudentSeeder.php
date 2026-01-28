<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;

class TestStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test class
        $class = SchoolClass::where('name', '10')->where('section', 'A')->first();
        if (!$class) {
            $class = SchoolClass::create([
                'school_id' => 1,
                'name' => '10',
                'section' => 'A',
                'status' => 'active'
            ]);
        }

        // Create Test Student 1
        $user1 = User::updateOrCreate(
            ['email' => 'student1@test.com'],
            [
                'name' => 'Test Student 1',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'school_id' => 1,
                'firebase_uid' => 'test_uid_1_' . time(),
                'status' => 'active'
            ]
        );

        Student::updateOrCreate(
            ['user_id' => $user1->id],
            [
                'school_id' => 1,
                'admission_no' => 'STU001',
                'class_id' => $class->id,
                'status' => 'active'
            ]
        );

        // Create Test Student 2
        $user2 = User::updateOrCreate(
            ['email' => 'student2@test.com'],
            [
                'name' => 'Test Student 2',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'school_id' => 1,
                'firebase_uid' => 'test_uid_2_' . time(),
                'status' => 'active'
            ]
        );

        Student::updateOrCreate(
            ['user_id' => $user2->id],
            [
                'school_id' => 1,
                'admission_no' => 'STU002',
                'class_id' => $class->id,
                'status' => 'active'
            ]
        );

        // Create Test Faculty
        $faculty = User::updateOrCreate(
            ['email' => 'faculty@test.com'],
            [
                'name' => 'Test Faculty',
                'password' => Hash::make('password123'),
                'role' => 'faculty',
                'school_id' => 1,
                'firebase_uid' => 'faculty_uid_' . time(),
                'status' => 'active'
            ]
        );

        // Create Test Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'school_id' => 1,
                'firebase_uid' => 'admin_uid_' . time(),
                'status' => 'active'
            ]
        );

        $this->command->info('Test users created successfully!');
        $this->command->info('Student 1: student1@test.com / password123');
        $this->command->info('Student 2: student2@test.com / password123');
        $this->command->info('Faculty: faculty@test.com / password123');
        $this->command->info('Admin: admin@test.com / password123');
    }
}
