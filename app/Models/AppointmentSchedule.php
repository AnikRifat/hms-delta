<?php
// app/Models/AppointmentSchedule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'day_of_week', 'start_time', 'end_time'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}