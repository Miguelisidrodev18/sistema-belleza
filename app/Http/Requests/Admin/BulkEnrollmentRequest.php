<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BulkEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_section_id' => ['required', 'integer', 'exists:course_sections,id'],
            'alumno_ids'        => ['required', 'array', 'min:1'],
            'alumno_ids.*'      => ['integer', 'exists:users,id'],
            'remarks'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_section_id.required' => 'Debes seleccionar una sección.',
            'alumno_ids.required'        => 'Debes seleccionar al menos un alumno.',
            'alumno_ids.min'             => 'Debes seleccionar al menos un alumno.',
        ];
    }
}
