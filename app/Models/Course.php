<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'program_id',
        'name',
        'code',
        'slug',
        'description',
        'hours',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'hours'      => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
