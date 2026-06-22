<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:200'],
            'code'        => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'hours'       => ['required', 'integer', 'min:1'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El nombre del curso es obligatorio.',
            'hours.required' => 'Las horas del curso son obligatorias.',
        ];
    }
}
