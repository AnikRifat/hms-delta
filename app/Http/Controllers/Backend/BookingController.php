<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\AppointmentSchedule;
use App\Models\Booking;
use App\Models\Department;
use App\Models\Doctor;
use App\Service\SmsService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['booking.view']);

        return view('backend.pages.bookings.index', [
            'bookings' => Booking::with('appointmentSchedule.doctor')->get(),
            'doctors' => Doctor::with('appointmentSchedules')->get(),
        ]);
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['booking.create']);

        return view('backend.pages.bookings.create', [
            'appointmentSchedules' => AppointmentSchedule::with('doctor')->get(),
            'doctors' => Doctor::whereHas('appointmentSchedules')->get(),
            'departments' => Department::whereHas('doctors.appointmentSchedules')->with('doctors')->get(),
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(BookingRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['booking.create']);

        // Create the booking
        $booking = Booking::create($request->validated());

        // Extract details for SMS
        $patientName = $request->input('patient_name');
        $doctorName = $booking->appointmentSchedule->doctor->name;
        $departmentName = $booking->appointmentSchedule->doctor->department->name;
        $appointmentDate = $booking->booking_date;
        $scheduleTime = $booking->appointmentSchedule->start_time.' - '.$booking->appointmentSchedule->end_time;
        $patientPhone = $request->input('patient_phone');
        $sl_no = $booking->sl_no;
        $room_no = $booking->appointmentSchedule->doctor->room_no;

        try {
            // Attempt to send the SMS
            if ($patientPhone) {
                $smsService = new SmsService;
                $messageBody = $smsService->composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime, $sl_no, $room_no);
                $smsService->sendSingleSms($patientPhone, $messageBody);
            }
        } catch (\Exception $e) {
            // Catch the error and dump the message for debugging
            Log::error('Error sending SMS: '.$e->getMessage());
            dd('Error sending SMS: '.$e->getMessage());
        }
        session()->flash('success', __('Booking has been created and confirmation SMS sent.'));

        return redirect()->route('admin.bookings.index');
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['booking.edit']);
        $booking = Booking::findOrFail($id);
        return view('backend.pages.bookings.edit', [
            'booking' => $booking,
            'appointmentSchedules' => AppointmentSchedule::with('doctor')->get(),
            'doctors' => Doctor::whereHas('appointmentSchedules')->get(),
            'departments' => Department::whereHas('doctors.appointmentSchedules')->with('doctors')->get(),

        ]);
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(BookingRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['booking.edit']);

        $booking = Booking::findOrFail($id);
        $data =$request->validated();
        if(isset($request->sl_no)){
            $data['sl_no'] = $request->sl_no;
        }
        $booking->update($data);

        session()->flash('success', __('Booking has been updated.'));

        return redirect()->route('admin.bookings.index');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['booking.delete']);

        $booking = Booking::findOrFail($id);
        $booking->delete();

        session()->flash('success', __('Booking has been deleted.'));

        return back();
    }
}
