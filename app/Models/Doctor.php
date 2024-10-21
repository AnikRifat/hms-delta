<?php

// app/Models/Doctor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'specialization', 'department_id', 'room_no'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointmentSchedules()
    {
        return $this->hasMany(AppointmentSchedule::class);
    }
}
