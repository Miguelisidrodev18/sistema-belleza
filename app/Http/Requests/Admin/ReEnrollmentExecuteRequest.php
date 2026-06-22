<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReEnrollmentExecuteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment_ids'   => ['required', 'array', 'min:1'],
            'enrollment_ids.*' => ['integer', 'exists:enrollments,id'],
            'target_period_id' => ['required', 'exists:academic_periods,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_ids.required' => 'Debes seleccionar al menos un alumno.',
            'enrollment_ids.min'      => 'Debes seleccionar al menos un alumno.',
        ];
    }
}
