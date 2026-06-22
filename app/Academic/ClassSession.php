<?php

namespace App\Academic;

use App\Enums\ClassSessionStatus;
use App\Enums\ScheduleModality;
use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClassSession extends Model
{
    protected $table = 'class_sessions';

    protected $fillable = [
        'course_section_id',
        'schedule_id',
        'session_number',
        'title',
        'starts_at',
        'ends_at',
        'room',
        'modality',
        'status',
        'is_generated',
        'notes',
        'cancelled_reason',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'status'       => ClassSessionStatus::class,
        'modality'     => ScheduleModality::class,
        'is_generated' => 'boolean',
    ];

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function meeting(): HasOne
    {
        return $this->hasOne(Meeting::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('starts_at', today());
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', ClassSessionStatus::Completed->value);
    }

    public function getEffectiveRoomAttribute(): ?string
    {
        return $this->room ?? $this->schedule?->room;
    }

    public function getEffectiveModalityAttribute(): ?ScheduleModality
    {
        return $this->modality ?? $this->schedule?->modality;
    }

    public function getIsPastAttribute(): bool
    {
        return $this->ends_at->isPast();
    }
}
