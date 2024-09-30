<?php

use Database\Seeders\AppointmentScheduleSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DoctorSeeder;
use Database\Seeders\PatientSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DoctorSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(AppointmentScheduleSeeder::class);
        $this->call(BookingSeeder::class);
    }
}
