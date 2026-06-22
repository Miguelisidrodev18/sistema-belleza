<?php

namespace App\Http\Requests\Admin;

use App\Enums\CourseSectionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'          => ['required', 'exists:courses,id'],
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'section_code'       => ['required', 'string', 'max:10'],
            'capacity'           => ['required', 'integer', 'min:1', 'max:500'],
            'status'             => ['required', Rule::enum(CourseSectionStatus::class)],
            'is_active'          => ['boolean'],
            'teacher_id'         => ['nullable', 'exists:users,id'],
            'teacher_role'       => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required'          => 'El curso es obligatorio.',
            'course_id.exists'            => 'El curso seleccionado no existe.',
            'academic_period_id.required' => 'El período académico es obligatorio.',
            'academic_period_id.exists'   => 'El período académico seleccionado no existe.',
            'section_code.required'       => 'El código de sección es obligatorio.',
            'capacity.required'           => 'La capacidad es obligatoria.',
            'status.required'             => 'El estado es obligatorio.',
        ];
    }
}
