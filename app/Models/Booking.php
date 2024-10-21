<?php

// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['patient_name', 'patient_email', 'patient_phone', 'appointment_schedule_id', 'booking_date'];

    public function appointmentSchedule()
    {
        return $this->belongsTo(AppointmentSchedule::class);
    }
    protected static function boot()
    {
        parent::boot();

        // Automatically generate serial number when creating a new Booking
        static::creating(function ($booking) {
            $latestBooking = Booking::where('appointment_schedule_id', $booking->appointment_schedule_id)
                ->whereDate('booking_date', $booking->booking_date)
                ->orderBy('sl_no', 'desc')
                ->first();

            // If there's no previous booking, start from 1, else increment the last serial number
            $booking->sl_no = $latestBooking ? $latestBooking->sl_no + 1 : 5;
        });
    }
}
