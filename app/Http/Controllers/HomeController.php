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
        if($booking){

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

        // Compose the message body
        $messageBody = $this->composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime);



        return view('success', compact('doctorName', 'bookingDate', 'scheduleTime'));
    }

    private function sendSMS($phoneNumber, $messageBody) {
        $smsService = new SmsService ;
        $smsService->sendSingleSms($phoneNumber, $messageBody);
    }
    private function composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime) {
        $message = "Dear $patientName,\n\n";
        $message .= "Your appointment has been successfully booked!\n";
        $message .= "Here are your appointment details:\n\n";
        $message .= "Doctor: Dr. $doctorName\n";
        $message .= "Department: $departmentName\n";
        $message .= "Appointment Date: $appointmentDate\n";
        $message .= "Time: $scheduleTime\n\n";
        $message .= "Please arrive 10 minutes early and bring any necessary documents with you.\n";
        $message .= "If you need to reschedule or have any questions, feel free to contact us.\n\n";
        $message .= "Thank you for choosing our healthcare services!\n";
        $message .= "We look forward to serving you.\n\n";
        $message .= "Best regards,\n";
        $message .= "[Your Clinic Name]";

        return $message;
    }
}
