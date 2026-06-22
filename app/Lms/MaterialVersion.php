<?php

namespace App\Lms;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialVersion extends Model
{
    protected $table = 'material_versions';

    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'version_number',
        'notes',
        'is_current',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'is_current'  => 'boolean',
        'created_at'  => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MaterialAttachment::class)->orderBy('sort');
    }

    public function getDownloadCountAttribute(): int
    {
        return $this->attachments->sum(fn ($a) => $a->downloads()->count());
    }
}
