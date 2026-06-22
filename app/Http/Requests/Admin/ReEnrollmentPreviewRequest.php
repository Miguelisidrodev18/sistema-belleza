<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReEnrollmentPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_period_id' => ['required', 'exists:academic_periods,id'],
            'target_period_id' => ['required', 'exists:academic_periods,id', 'different:source_period_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'source_period_id.required'  => 'Selecciona el período de origen.',
            'target_period_id.required'  => 'Selecciona el período de destino.',
            'target_period_id.different' => 'El período de destino debe ser diferente al de origen.',
        ];
    }
}
