<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Department;
use App\Service\SmsService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function redirectAdmin()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $departments = Department::whereHas('doctors.appointmentSchedules')->with('doctors')->get();

        return view('welcome', compact('departments'));
    }

    public function storeBooking(BookingRequest $request)
    {
        $booking = Booking::create($request->validated());
        if ($booking) {
        }
        session()->flash('success', __('Booking has been created.'));

        return $this->showSuccessPage($booking['id']);
    }

    public function showSuccessPage($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $doctorName = $booking->appointmentSchedule->doctor->name;
        $bookingDate = $booking->booking_date;
        $scheduleTime = $booking->appointmentSchedule->start_time.' - '.$booking->appointmentSchedule->end_time;
        // Extract details for SMS
        $departmentName = $booking->appointmentSchedule->doctor->department->name;
        $appointmentDate = $booking->booking_date;
        $patientPhone = $booking->patient_phone;
        $patientName = $booking->patient_name;
        $sl_no = $booking->sl_no;
        $room_no = $booking->appointmentSchedule->doctor->room_no;
        $smsService = new SmsService;
        // Compose the message body
        $messageBody = $smsService->composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime, $sl_no, $room_no);
        $smsService->sendSingleSms($patientPhone, $messageBody);

        return view('success', compact('doctorName', 'bookingDate', 'scheduleTime'));
    }

    private function sendSMS($phoneNumber, $messageBody)
    {

    }
}
