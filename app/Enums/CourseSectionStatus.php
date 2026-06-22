<?php

namespace App\Enums;

enum CourseSectionStatus: string
{
    case Draft     = 'draft';
    case Published = 'published';
    case Closed    = 'closed';
    case Finished  = 'finished';
    case Archived  = 'archived';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Borrador',
            self::Published => 'Publicada',
            self::Closed    => 'Cerrada',
            self::Finished  => 'Finalizada',
            self::Archived  => 'Archivada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'gray',
            self::Published => 'green',
            self::Closed    => 'yellow',
            self::Finished  => 'blue',
            self::Archived  => 'red',
        };
    }

    public function isVisible(): bool
    {
        return $this === self::Published;
    }
}
