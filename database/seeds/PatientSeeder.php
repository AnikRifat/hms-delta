<?php
// database/seeders/PatientSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        $patients = [
            ['name' => 'Alice Green', 'email' => 'alice@example.com', 'phone' => '1231231234', 'dob' => '1990-01-01'],
            ['name' => 'Bob White', 'email' => 'bob@example.com', 'phone' => '2342342345', 'dob' => '1985-05-15'],
            ['name' => 'Charlie Black', 'email' => 'charlie@example.com', 'phone' => '3453453456', 'dob' => '1992-08-20'],
            ['name' => 'Diana Blue', 'email' => 'diana@example.com', 'phone' => '4564564567', 'dob' => '1980-12-30'],
            ['name' => 'Eva Red', 'email' => 'eva@example.com', 'phone' => '5675675678', 'dob' => '1975-03-10'],
        ];

        Patient::insert($patients);
    }
}
