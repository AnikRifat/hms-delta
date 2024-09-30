<?php
// app/Http/Requests/DepartmentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:departments,name' . ($this->department ? ",{$this->department}" : ''),
        ];
    }

    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }
}
