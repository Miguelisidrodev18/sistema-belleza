<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $fillable = [
        'enrollment_number',
        'alumno_id',
        'course_section_id',
        'academic_period_id',
        'status',
        'enrolled_at',
        'withdrawn_at',
        'completed_at',
        'remarks',
    ];

    protected $casts = [
        'status'       => EnrollmentStatus::class,
        'enrolled_at'  => 'date',
        'withdrawn_at' => 'date',
        'completed_at' => 'date',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(EnrollmentActivity::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', EnrollmentStatus::Activa->value);
    }
}
