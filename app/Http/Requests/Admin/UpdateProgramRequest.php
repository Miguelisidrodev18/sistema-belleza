<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:150'],
            'description'          => ['nullable', 'string'],
            'short_description'    => ['nullable', 'string', 'max:500'],
            'certificate_name'     => ['nullable', 'string', 'max:255'],
            'certificate_template' => ['nullable', 'string', 'max:255'],
            'color'                => ['required', 'string', 'max:20'],
            'icon'                 => ['nullable', 'string', 'max:50'],
            'duration_months'      => ['required', 'integer', 'min:1', 'max:120'],
            'total_hours'          => ['required', 'integer', 'min:1'],
            'is_active'            => ['boolean'],
            'sort_order'           => ['integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El nombre del programa es obligatorio.',
            'color.required'           => 'El color de marca es obligatorio.',
            'duration_months.required' => 'La duración en meses es obligatoria.',
            'total_hours.required'     => 'El total de horas es obligatorio.',
        ];
    }
}
