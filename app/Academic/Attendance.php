<?php

namespace App\Academic;

use App\Enums\AttendanceStatus;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';

    public $timestamps = false;

    protected $fillable = [
        'class_session_id',
        'enrollment_id',
        'status',
        'arrival_time',
        'departure_time',
        'notes',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'status'      => AttendanceStatus::class,
        'recorded_at' => 'datetime',
    ];

    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getMinutesLateAttribute(): ?int
    {
        if ($this->status !== AttendanceStatus::Late || ! $this->arrival_time || ! $this->classSession) {
            return null;
        }
        $sessionStart = Carbon::parse($this->classSession->starts_at->format('H:i'));
        $arrival      = Carbon::parse($this->arrival_time);
        return max(0, (int) $sessionStart->diffInMinutes($arrival, false));
    }

    public function getMinutesConnectedAttribute(): ?int
    {
        if (! $this->arrival_time || ! $this->departure_time) {
            return null;
        }
        return (int) Carbon::parse($this->arrival_time)->diffInMinutes(Carbon::parse($this->departure_time));
    }
}
