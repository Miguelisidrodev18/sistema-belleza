<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alumno_id'         => ['required', 'exists:users,id'],
            'course_section_id' => ['required', 'exists:course_sections,id'],
            'remarks'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alumno_id.required'         => 'Debe seleccionar un alumno.',
            'alumno_id.exists'           => 'El alumno seleccionado no existe.',
            'course_section_id.required' => 'Debe seleccionar una sección.',
            'course_section_id.exists'   => 'La sección seleccionada no existe.',
        ];
    }
}
