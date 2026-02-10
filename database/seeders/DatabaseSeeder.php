<?php

namespace Database\Seeders;

use App\Events\StudentCreationEvent;
use App\Models\AcademicPeriod;
use App\Models\Curriculum;
use App\Models\Department;
use App\Models\Program;
use App\Models\Student;
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
            'year_start' => '2023',
            'year_end' => '2025',
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 110',
            'name' => 'Introduction to Computing',
            'year_level' => 1,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 111',
            'name' => 'Computer Programming 1',
            'year_level' => 1,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 112',
            'name' => 'Intro to Graphics and Design',
            'year_level' => 1,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 120',
            'name' => 'Computer Programming 2',
            'year_level' => 1,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 121',
            'name' => 'Operating Systems',
            'year_level' => 1,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 122',
            'name' => 'Intro to Web Design',
            'year_level' => 1,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 123',
            'name' => 'Applications Dev\'t and Emerging Tech',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 124',
            'name' => 'Fundamentals of Database Systems',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 125',
            'name' => 'Data Structures and Algorithms',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 210',
            'name' => 'Discrete Structures 1',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 211',
            'name' => 'Object-oriented Programming',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);
        
        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'STAT 01C',
            'name' => 'Probability and Statistics',
            'year_level' => 2,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 126',
            'name' => 'Information Management',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 127',
            'name' => 'Advanced Database Systems',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 221',
            'name' => 'Digital Design and Electronics',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 222',
            'name' => 'Computer Architecture',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 223',
            'name' => 'Discrete Structures 2',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 224',
            'name' => 'Networks and Communication',
            'year_level' => 2,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 129',
            'name' => 'Networks and Communication',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 310',
            'name' => 'Software Engineering 1',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 311',
            'name' => 'Computer Programming 3',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 312',
            'name' => 'Algorithms and Complexity',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 313',
            'name' => 'Elective 1',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 314',
            'name' => 'Linear Algebra',
            'year_level' => 3,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 130',
            'name' => 'Computer Accounting (w/SAP)',
            'year_level' => 3,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 320',
            'name' => 'Software Engineering 2',
            'year_level' => 3,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 321',
            'name' => 'Programming Languages',
            'year_level' => 3,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 322',
            'name' => 'Elective 2',
            'year_level' => 3,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 323',
            'name' => 'Math Analysis',
            'year_level' => 3,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 5.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        // Subject::factory()->create([
        //     'curriculum_id' => 1,
        //     'code' => 'CS 330',
        //     'name' => 'Computer Practicum (488 hours)',
        //     'year_level' => 3,
        //     'semester' => 'FIRST',
        //     'subject_category' => 'MAJOR',
        //     'lec_units' => 0.0,
        //     'lab_units' => 0.0,
        //     'prerequisites' => null,
        //     'is_active' => true,
        // ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'ITC 128',
            'name' => 'Social Issues and Professional Practice',
            'year_level' => 4,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 411',
            'name' => 'Thesis 1',
            'year_level' => 4,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 412',
            'name' => 'Elective 3',
            'year_level' => 4,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 413',
            'name' => 'Automata Theory and Formal Languages',
            'year_level' => 4,
            'semester' => 'FIRST',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 420',
            'name' => 'Information Assurance and Security',
            'year_level' => 4,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 421',
            'name' => 'Thesis 2',
            'year_level' => 4,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 422',
            'name' => 'Human Computer Interaction',
            'year_level' => 4,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'CS 423',
            'name' => 'Elective 4',
            'year_level' => 4,
            'semester' => 'SECOND',
            'subject_category' => 'MAJOR',
            'lec_units' => 2.0,
            'lab_units' => 1.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 05',
            'name' => 'Mathematics in the Modern World',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 06',
            'name' => 'Purposive Communication',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 07',
            'name' => 'Science, Technology, and Society',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 15',
            'name' => 'PE 1 - Movement Enhancement',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 2.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 19',
            'name' => 'NSTP 1',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 01',
            'name' => 'Art Appreciation',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 02',
            'name' => 'Ethics',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 04',
            'name' => 'Readings in Philippine History',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 08',
            'name' => 'Understanding the Self',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 16',
            'name' => 'PE 2 - Fitness Exercises',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 2.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 20',
            'name' => 'NSTP 2',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 03',
            'name' => 'Contemporary World',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 17',
            'name' => 'PE 3 - Dance/Swimming',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 2.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 18',
            'name' => 'PE 4 - Team Sports',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 2.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 14',
            'name' => 'World Literature',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 09',
            'name' => 'Rizal',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS 11',
            'name' => 'Filipino 2 - Panitikan',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        Subject::factory()->create([
            'curriculum_id' => 1,
            'code' => 'GCAS IR',
            'name' => 'Community Development',
            'year_level' => null,
            'semester' => null,
            'subject_category' => 'MINOR',
            'lec_units' => 3.0,
            'lab_units' => 0.0,
            'prerequisites' => null,
            'is_active' => true,
        ]);

        $student = Student::factory()->create([
            'user_id' => null,
            'program_id' => 1,
            'curriculum_id' => 1,
            'student_number' => '23-12345',
            'year_level' => 1,
        ]);

        event(new StudentCreationEvent($student, [
            'name' => 'testStudent',
            'password' => '123456'
        ]));

        $academicPeriod = AcademicPeriod::factory()->create([
            'name' => 'A.Y. 2025-2026 - 1st Semester',
            'school_year' => '2025-2026',
            'year_start' => '2025',
            'year_end' => '2026',
            'semester' => '1st',
            'is_current' => true,
        ]);

    }
}
