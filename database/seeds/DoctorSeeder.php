<?php
// database/seeders/DoctorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Department;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $doctors = [
            ['name' => 'Dr. John Smith', 'email' => 'john@example.com', 'phone' => '1234567890', 'specialization' => 'Cardiologist', 'department_id' => Department::inRandomOrder()->first()->id],
            ['name' => 'Dr. Emily Johnson', 'email' => 'emily@example.com', 'phone' => '0987654321', 'specialization' => 'Neurologist', 'department_id' => Department::inRandomOrder()->first()->id],
            ['name' => 'Dr. Michael Brown', 'email' => 'michael@example.com', 'phone' => '5555555555', 'specialization' => 'Orthopedic Surgeon', 'department_id' => Department::inRandomOrder()->first()->id],
            ['name' => 'Dr. Sarah Davis', 'email' => 'sarah@example.com', 'phone' => '6666666666', 'specialization' => 'Pediatrician', 'department_id' => Department::inRandomOrder()->first()->id],
            ['name' => 'Dr. Jessica Wilson', 'email' => 'jessica@example.com', 'phone' => '7777777777', 'specialization' => 'Dermatologist', 'department_id' => Department::inRandomOrder()->first()->id],
        ];

        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
