<?php

namespace App\Http\Requests\Admin;

use App\Enums\AcademicPeriodStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $periodId = $this->route('academic_period')->id;

        return [
            'name'               => ['required', 'string', 'max:100'],
            'start_date'         => ['required', 'date'],
            'end_date'           => ['required', 'date', 'after:start_date'],
            'status'             => ['required', Rule::enum(AcademicPeriodStatus::class)],
            'previous_period_id' => [
                'nullable',
                'exists:academic_periods,id',
                Rule::notIn([$periodId]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'               => 'El nombre del período es obligatorio.',
            'start_date.required'         => 'La fecha de inicio es obligatoria.',
            'end_date.required'           => 'La fecha de fin es obligatoria.',
            'end_date.after'              => 'La fecha de fin debe ser posterior a la de inicio.',
            'status.required'             => 'El estado es obligatorio.',
            'previous_period_id.exists'   => 'El período anterior seleccionado no existe.',
            'previous_period_id.not_in'   => 'Un período no puede referenciarse a sí mismo.',
        ];
    }
}
