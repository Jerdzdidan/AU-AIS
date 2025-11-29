<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\Department;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use COM;
use CurlHandle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'root',
            'email' => 'root@gmail.com',
            'password' => '123456',
            'user_type' => 'ADMIN',
            'status' => true,
        ]);

        Department::factory()->create([
            'name' => 'School of Computer Studies',
            'code' => 'SCS',
            'head_of_department' => 'Geraldine M. Rilles'
        ]);

        User::factory()->create([
            'name' => 'officer1',
            'email' => 'officer1@gmail.com',
            'password' => '123456',
            'user_type' => 'OFFICER',
            'status' => true,
            'department_id' => 1,
        ]);

        Program::factory()->create([
            'name' => 'Bachelor of Science in Computer Science',
            'code' => 'BSCS',
            'department_id' => 1,
        ]);

        Curriculum::factory()->create([
            'program_id' => 1,
            'description' => 'BSCS Curriculum 2025',
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS101',
            'name' => 'Introduction to Computer Science',
            'year_level' => 1,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 2.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);
    }
}
