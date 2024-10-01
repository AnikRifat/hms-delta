<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRequest; // Make sure to create this request
use App\Http\Requests\AppointmentScheduleRequest; // Make sure to create this request
use App\Models\Doctor;
use App\Models\Department;
use App\Models\AppointmentSchedule; // Include the AppointmentSchedule model
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class DoctorController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.view']);

        return view('backend.pages.doctors.index', [
            'doctors' => Doctor::with('department')->get(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.create']);

        return view('backend.pages.doctors.create', [
            'departments' => Department::all(),
        ]);
    }

    public function store(DoctorRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.create']);

        // Create the doctor
        $doctor = Doctor::create($request->validated());

        // Handle appointment schedules
        $dayOfWeek = $request->input('day_of_week');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        // Loop through schedules and create them
        if ($dayOfWeek && $startTime && $endTime) {
            foreach ($dayOfWeek as $key => $day) {
                AppointmentSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => $startTime[$key],
                    'end_time' => $endTime[$key],
                ]);
            }
        }

        session()->flash('success', __('Doctor has been created.'));
        return redirect()->route('admin.doctors.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['doctor.edit']);

        $doctor = Doctor::with('department', 'appointmentSchedules')->findOrFail($id);

        return view('backend.pages.doctors.edit', [
            'doctor' => $doctor,
            'departments' => Department::all(),
            'schedules' => $doctor->appointmentSchedules, // Pass the existing schedules
        ]);
    }

    public function update(DoctorRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.edit']);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->validated());

        // Handle appointment schedules
        $schedulesData = [];
        foreach ($request->day_of_week as $index => $day) {
            if ($day && $request->start_time[$index] && $request->end_time[$index]) {
                $schedulesData[] = [
                    'day_of_week' => $day,
                    'start_time' => $request->start_time[$index],
                    'end_time' => $request->end_time[$index],
                    'doctor_id' => $doctor->id,
                ];
            }
        }

        // Clear existing schedules and create new ones
        $doctor->appointmentSchedules()->delete();
        $doctor->appointmentSchedules()->createMany($schedulesData);

        session()->flash('success', __('Doctor has been updated.'));
        return redirect()->route('admin.doctors.index');
    }


    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['doctor.delete']);

        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        session()->flash('success', __('Doctor has been deleted.'));
        return back();
    }
}
