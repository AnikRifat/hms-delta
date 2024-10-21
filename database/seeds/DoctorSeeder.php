<?php

// database/seeders/DoctorSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $doctors = [
            ['room_no' => 1, 'name' => 'Dr. John Smith', 'email' => 'john@example.com', 'phone' => '1234567890', 'specialization' => 'Cardiologist', 'department_id' => Department::inRandomOrder()->first()->id],
            ['room_no' => 1, 'name' => 'Dr. Emily Johnson', 'email' => 'emily@example.com', 'phone' => '0987654321', 'specialization' => 'Neurologist', 'department_id' => Department::inRandomOrder()->first()->id],
            ['room_no' => 1, 'name' => 'Dr. Michael Brown', 'email' => 'michael@example.com', 'phone' => '5555555555', 'specialization' => 'Orthopedic Surgeon', 'department_id' => Department::inRandomOrder()->first()->id],
            ['room_no' => 1, 'name' => 'Dr. Sarah Davis', 'email' => 'sarah@example.com', 'phone' => '6666666666', 'specialization' => 'Pediatrician', 'department_id' => Department::inRandomOrder()->first()->id],
            ['room_no' => 1, 'name' => 'Dr. Jessica Wilson', 'email' => 'jessica@example.com', 'phone' => '7777777777', 'specialization' => 'Dermatologist', 'department_id' => Department::inRandomOrder()->first()->id],
        ];

        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
