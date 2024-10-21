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
            'sl_no' => 'required|numeric',
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'nullable|email|max:255',
            'patient_phone' => [
                'string',
                'max:20',
                'regex:/^(\+8801[3-9]\d{8}|01[3-9]\d{8})$/', // Validates Bangladeshi phone numbers
            ],
            'appointment_schedule_id' => 'required|exists:appointment_schedules,id',
            'booking_date' => 'required|date',
        ];
    }

    /**
     * Get the validation error messages.
     */
    public function messages()
    {
        return [
            'patient_phone.regex' => 'The patient phone number must be a valid Bangladeshi phone number.',
        ];
    }
}
