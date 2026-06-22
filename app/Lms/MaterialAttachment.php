<?php

namespace App\Lms;

use App\Enums\MaterialAttachmentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class MaterialAttachment extends Model
{
    protected $table = 'material_attachments';

    public $timestamps = false;

    protected $fillable = [
        'material_version_id',
        'type',
        'title',
        'original_name',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
        'sort',
        'created_at',
    ];

    protected $casts = [
        'type'       => MaterialAttachmentType::class,
        'created_at' => 'datetime',
    ];

    public function materialVersion(): BelongsTo
    {
        return $this->belongsTo(MaterialVersion::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(MaterialDownload::class);
    }

    public function url(): string
    {
        if ($this->isLink()) {
            return $this->path;
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    public function isLink(): bool
    {
        return $this->type === MaterialAttachmentType::Link;
    }

    public function humanSize(): string
    {
        if ($this->size_bytes === null) {
            return '—';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->size_bytes;
        $unit  = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 1) . ' ' . $units[$unit];
    }
}
