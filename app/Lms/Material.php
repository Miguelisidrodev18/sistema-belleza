<?php

namespace App\Lms;

use App\Enums\MaterialVisibility;
use App\Models\CourseSection;
use App\Academic\ClassSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
        'course_section_id',
        'class_session_id',
        'title',
        'description',
        'visibility',
        'is_published',
        'order',
        'created_by',
    ];

    protected $casts = [
        'visibility'   => MaterialVisibility::class,
        'is_published' => 'boolean',
    ];

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(MaterialVersion::class)->orderByDesc('version_number');
    }

    public function currentVersion(): HasOne
    {
        return $this->hasOne(MaterialVersion::class)->where('is_current', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForSection($query, int $sectionId)
    {
        return $query->where('course_section_id', $sectionId)->whereNull('class_session_id');
    }

    public function scopeForSession($query, int $sessionId)
    {
        return $query->where('class_session_id', $sessionId);
    }
}
