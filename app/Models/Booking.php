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
}
