<?php

namespace App\Enums;

enum ClassSessionStatus: string
{
    case Scheduled  = 'scheduled';
    case InProgress = 'in_progress';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled  => 'Programada',
            self::InProgress => 'En progreso',
            self::Completed  => 'Completada',
            self::Cancelled  => 'Cancelada',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Scheduled  => 'bg-blue-100 text-blue-800',
            self::InProgress => 'bg-yellow-100 text-yellow-800',
            self::Completed  => 'bg-green-100 text-green-800',
            self::Cancelled  => 'bg-red-100 text-red-800',
        };
    }
}
