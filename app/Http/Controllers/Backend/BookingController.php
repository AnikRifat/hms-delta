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

        // Create the booking
        $booking = Booking::create($request->validated());

        // Send SMS after storing the booking
        $patientName = $request->input('patient_name');
        $doctorName = $booking->appointmentSchedule->doctor->name;
        $departmentName = $booking->appointmentSchedule->doctor->department->name;
        $appointmentDate = $booking->booking_date;
        $scheduleTime = $booking->appointmentSchedule->start_time . ' - ' . $booking->appointmentSchedule->end_time;
        $patientPhone = $request->input('patient_phone'); // Assuming the phone number is provided

        // Compose the message body
        $messageBody = $this->composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime);

        // Send the SMS (you can replace `sendSMS` with your actual SMS sending method)
        if ($patientPhone) {
            $this->sendSMS($patientPhone, $messageBody);
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


    private function sendSMS($phoneNumber, $messageBody) {
        // SSLCommerz API credentials
        $apiUrl = 'https://api.sslcommerz.com/sms/send'; // This is the endpoint for sending SMS
        $apiKey = 'DELTAHCCTGAPI'; // Replace with your SSLCommerz API key
        $apiSecret = '6mpxigx9-r9qtczun-dhepssss-gdpbdn02-miqbe0wz'; // Replace with your SSLCommerz API secret

        // Prepare the data to be sent
        $data = [
            'to' => $phoneNumber,
            'message' => $messageBody,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ];

        // Initialize cURL
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_POST, true); // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send data
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification

        // Execute the request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            return false;
        }

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseData = json_decode($response, true);
        // Check the response
        if (isset($responseData['success']) && $responseData['success'] === true) {
            return true; // SMS sent successfully
        } else {
            // Handle errors
            echo 'Error: ' . $responseData['message'] ?? 'Unknown error occurred';
            return false;
        }
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
