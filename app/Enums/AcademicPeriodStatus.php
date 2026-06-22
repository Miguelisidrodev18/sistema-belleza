<?php

namespace App\Enums;

enum AcademicPeriodStatus: string
{
    case Planificacion = 'planificacion';
    case Activo        = 'activo';
    case Finalizado    = 'finalizado';

    public function label(): string
    {
        return match($this) {
            self::Planificacion => 'En Planificación',
            self::Activo        => 'Activo',
            self::Finalizado    => 'Finalizado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Planificacion => 'yellow',
            self::Activo        => 'green',
            self::Finalizado    => 'gray',
        };
    }
}
