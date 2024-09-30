<?php
// database/seeders/AppointmentScheduleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentSchedule;
use App\Models\Doctor;

class AppointmentScheduleSeeder extends Seeder
{
    public function run()
    {
        $schedules = [
            ['doctor_id' => Doctor::inRandomOrder()->first()->id, 'day_of_week' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '17:00:00'],
            ['doctor_id' => Doctor::inRandomOrder()->first()->id, 'day_of_week' => 'Tuesday', 'start_time' => '09:00:00', 'end_time' => '17:00:00'],
            ['doctor_id' => Doctor::inRandomOrder()->first()->id, 'day_of_week' => 'Wednesday', 'start_time' => '09:00:00', 'end_time' => '17:00:00'],
            ['doctor_id' => Doctor::inRandomOrder()->first()->id, 'day_of_week' => 'Thursday', 'start_time' => '09:00:00', 'end_time' => '17:00:00'],
            ['doctor_id' => Doctor::inRandomOrder()->first()->id, 'day_of_week' => 'Friday', 'start_time' => '09:00:00', 'end_time' => '17:00:00'],
        ];

        foreach ($schedules as $schedule) {
            AppointmentSchedule::create($schedule);
        }
    }
}
