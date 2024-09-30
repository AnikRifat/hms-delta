<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming that authorization is handled elsewhere, we return true here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:doctors,email,' . $this->route('doctor')],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
        ];
    }
}
