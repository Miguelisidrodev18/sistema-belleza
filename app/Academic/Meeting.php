<?php

namespace App\Academic;

use App\Enums\MeetingPlatform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $table = 'meetings';

    protected $fillable = [
        'class_session_id',
        'platform',
        'meeting_url',
        'meeting_id',
        'passcode',
        'host_url',
        'waiting_room',
        'started_at',
        'ended_at',
        'recording_url',
        'recording_duration',
        'status',
    ];

    protected $casts = [
        'platform'     => MeetingPlatform::class,
        'waiting_room' => 'boolean',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
    ];

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function getDurationMinutesAttribute(): ?int
    {
        if ($this->started_at && $this->ended_at) {
            return (int) $this->started_at->diffInMinutes($this->ended_at);
        }
        return null;
    }
}
