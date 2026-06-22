<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dni',
        'phone',
        'photo',
        'address',
        'birth_date',
        'gender',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    // --- Role helpers ---

    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    public function isDocente(): bool
    {
        return $this->role === 'docente';
    }

    public function isAlumno(): bool
    {
        return $this->role === 'alumno';
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'administrador' => 'admin.dashboard',
            'docente' => 'docente.dashboard',
            'alumno' => 'alumno.dashboard',
        };
    }

    // --- Relationships ---

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'alumno_id');
    }

    // --- Scopes ---

    public function scopeAdmins($query)
    {
        return $query->where('role', 'administrador');
    }

    public function scopeDocentes($query)
    {
        return $query->where('role', 'docente');
    }

    public function scopeAlumnos($query)
    {
        return $query->where('role', 'alumno');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // --- Accessors ---

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'administrador' => 'Administrador',
            'docente' => 'Docente',
            'alumno' => 'Alumno',
            default => $this->role,
        };
    }

    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }
        return $initials;
    }
}
