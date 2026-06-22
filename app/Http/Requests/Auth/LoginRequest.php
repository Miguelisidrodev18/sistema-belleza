<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:administrador,docente,alumno'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo o DNI es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'role.required' => 'Selecciona un rol.',
            'role.in' => 'El rol seleccionado no es válido.',
        ];
    }
}
