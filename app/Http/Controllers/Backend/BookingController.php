<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\AppointmentSchedule;
use App\Models\Booking;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

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

        Booking::create($request->validated());

        session()->flash('success', __('Booking has been created.'));

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
        $booking->update($request->validated());

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
