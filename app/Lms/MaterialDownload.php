<?php

namespace App\Lms;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialDownload extends Model
{
    protected $table = 'material_downloads';

    public $timestamps = false;

    protected $fillable = [
        'material_attachment_id',
        'alumno_id',
        'downloaded_at',
        'ip_address',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function materialAttachment(): BelongsTo
    {
        return $this->belongsTo(MaterialAttachment::class);
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}
