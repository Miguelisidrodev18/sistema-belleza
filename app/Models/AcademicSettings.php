<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicSettings extends Model
{
    protected $fillable = [
        'current_period_id',
        'default_capacity',
        'allow_overbooking',
        'default_timezone',
    ];

    protected $casts = [
        'default_capacity'  => 'integer',
        'allow_overbooking' => 'boolean',
    ];

    public function currentPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'current_period_id');
    }

    public static function get(): static
    {
        return static::firstOrCreate([], [
            'default_capacity'  => 30,
            'allow_overbooking' => false,
            'default_timezone'  => 'America/Lima',
        ]);
    }
}
