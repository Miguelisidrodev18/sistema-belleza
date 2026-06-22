<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Activa     = 'activa';
    case Completada = 'completada';
    case Suspendida = 'suspendida';
    case Retirada   = 'retirada';

    public function label(): string
    {
        return match ($this) {
            self::Activa     => 'Activa',
            self::Completada => 'Completada',
            self::Suspendida => 'Suspendida',
            self::Retirada   => 'Retirada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Activa     => 'green',
            self::Completada => 'blue',
            self::Suspendida => 'yellow',
            self::Retirada   => 'red',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Activa     => 'bg-green-100 text-green-800',
            self::Completada => 'bg-blue-100 text-blue-800',
            self::Suspendida => 'bg-yellow-100 text-yellow-800',
            self::Retirada   => 'bg-red-100 text-red-800',
        };
    }
}
