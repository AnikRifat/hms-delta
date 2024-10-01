<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $this->doctor,
            'phone' => 'nullable|string|max:15',
            'specialization' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'day_of_week' => 'required|array',
            'day_of_week.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|array',
            'start_time.*' => 'required',
            'end_time' => 'required|array',
            'end_time.*' => 'required|after:start_time.*',
        ];
    }

    public function messages()
    {
        return [
            'start_time.*.date_format' => 'The start time must be in the format HH:mm (24-hour).',
            'end_time.*.date_format' => 'The end time must be in the format HH:mm (24-hour).',
            'end_time.*.after' => 'The end time must be after the start time.',
        ];
    }
}
