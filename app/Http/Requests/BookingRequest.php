<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can update this condition to determine who is authorized to make the request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'nullable|email|max:255',
            'patient_phone' => 'nullable|string|max:20',
            'appointment_schedule_id' => 'required|exists:appointment_schedules,id',
            'booking_date' => 'required|date',
        ];
    }
}
