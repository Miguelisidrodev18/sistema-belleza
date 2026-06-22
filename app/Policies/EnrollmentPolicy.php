<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'docente', 'alumno']);
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return match ($user->role) {
            'administrador' => true,
            'alumno'        => $enrollment->alumno_id === $user->id,
            'docente'       => $enrollment->courseSection->teachers()->where('teacher_id', $user->id)->exists(),
            default         => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->role === 'administrador';
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->role === 'administrador';
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->role === 'administrador';
    }
}
