<?php

// database/seeders/BookingSeeder.php

namespace Database\Seeders;

use App\Models\AppointmentSchedule;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $bookings = [
            ['sl_no'=>1,'patient_name' => 'Alice Green', 'patient_email' => 'alice@example.com', 'patient_phone' => '1231231234', 'appointment_schedule_id' => AppointmentSchedule::inRandomOrder()->first()->id, 'booking_date' => '2024-10-01'],
            ['sl_no'=>1,'patient_name' => 'Bob White', 'patient_email' => 'bob@example.com', 'patient_phone' => '2342342345', 'appointment_schedule_id' => AppointmentSchedule::inRandomOrder()->first()->id, 'booking_date' => '2024-10-02'],
            ['sl_no'=>1,'patient_name' => 'Charlie Black', 'patient_email' => 'charlie@example.com', 'patient_phone' => '3453453456', 'appointment_schedule_id' => AppointmentSchedule::inRandomOrder()->first()->id, 'booking_date' => '2024-10-03'],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
