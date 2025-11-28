<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
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
    }
}
