<?php

// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Cardiology'],
            ['name' => 'Neurology'],
            ['name' => 'Orthopedics'],
            ['name' => 'Pediatrics'],
            ['name' => 'General Medicine'],
            ['name' => 'Dermatology'],
        ];

        Department::insert($departments);
    }
}
