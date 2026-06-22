<?php

namespace App\Enums;

enum ScheduleModality: string
{
    case Presencial = 'presencial';
    case Virtual    = 'virtual';
    case Hibrido    = 'hibrido';

    public function label(): string
    {
        return match ($this) {
            self::Presencial => 'Presencial',
            self::Virtual    => 'Virtual',
            self::Hibrido    => 'Híbrido',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Presencial => 'bg-green-100 text-green-800',
            self::Virtual    => 'bg-blue-100 text-blue-800',
            self::Hibrido    => 'bg-purple-100 text-purple-800',
        };
    }
}
