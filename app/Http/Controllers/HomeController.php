<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        $departments = Department::with('doctors')->get();
        return view('welcome',compact('departments'));
    }

    public function storeBooking(BookingRequest $request)
    {

        $booking =Booking::create($request->validated());

        session()->flash('success', __('Booking has been created.'));
        return $this->showSuccessPage($booking['id']);
    }

    public function showSuccessPage($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $doctorName = $booking->appointmentSchedule->doctor->name;
        $bookingDate = $booking->booking_date;
        $scheduleTime = $booking->appointmentSchedule->start_time . ' - ' . $booking->appointmentSchedule->end_time;

        return view('success', compact('doctorName', 'bookingDate', 'scheduleTime'));
    }
}
