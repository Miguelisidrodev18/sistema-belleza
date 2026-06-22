<?php

namespace App\Enums;

enum MaterialVisibility: string
{
    case Section = 'section';
    case Session = 'session';
    case Private = 'private';

    public function label(): string
    {
        return match ($this) {
            self::Section => 'General de sección',
            self::Session => 'Específico de sesión',
            self::Private => 'Privado',
        };
    }
}
