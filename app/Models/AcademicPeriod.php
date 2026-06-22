<?php

namespace App\Models;

use App\Enums\AcademicPeriodStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'is_current',
        'previous_period_id',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'is_current'  => 'boolean',
        'status'      => AcademicPeriodStatus::class,
    ];

    public function courseSections(): HasMany
    {
        return $this->hasMany(CourseSection::class);
    }

    public function previousPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'previous_period_id');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', AcademicPeriodStatus::Activo->value);
    }
}
