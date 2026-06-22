<?php

namespace App\Models;

use App\Academic\ClassSession;
use App\Academic\Schedule;
use App\Enums\CourseSectionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSection extends Model
{
    protected $fillable = [
        'course_id',
        'academic_period_id',
        'section_code',
        'capacity',
        'status',
        'is_active',
    ];

    protected $casts = [
        'capacity'  => 'integer',
        'is_active' => 'boolean',
        'status'    => CourseSectionStatus::class,
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_section_teachers', 'course_section_id', 'teacher_id')
            ->withPivot('role', 'is_primary')
            ->withTimestamps();
    }

    public function primaryTeacher(): ?User
    {
        return $this->teachers()->wherePivot('is_primary', true)->first();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }

    public function enrolledCount(): int
    {
        return $this->enrollments()->where('status', 'activa')->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->capacity - $this->enrolledCount());
    }

    public function getSectionNameAttribute(): string
    {
        return $this->course->name . ' — Sección ' . $this->section_code;
    }
}
