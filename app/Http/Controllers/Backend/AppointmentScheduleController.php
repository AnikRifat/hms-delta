<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AppointmentSchedule;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentScheduleController extends Controller
{
    public function getByDoctor(Request $request)
{
    $doctorId = $request->get('doctor_id');
    $schedules = AppointmentSchedule::where('doctor_id', $doctorId)->get();
    return response()->json($schedules);
}

public function getDoctors(Request $request)
{
    $departmentId = $request->get('department_id');

    $doctors = Doctor::with('appointmentSchedules')->where('department_id', $departmentId)->get();
    return response()->json($doctors);
}


}
