<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'certificate_name',
        'certificate_template',
        'color',
        'icon',
        'image',
        'duration_months',
        'total_hours',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'total_hours'     => 'integer',
        'sort_order'      => 'integer',
        'is_active'       => 'boolean',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->orderBy('sort_order');
    }

    public function courseSections(): HasManyThrough
    {
        return $this->hasManyThrough(CourseSection::class, Course::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
