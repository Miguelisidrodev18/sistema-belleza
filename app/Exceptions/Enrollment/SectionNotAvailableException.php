<?php

namespace App\Exceptions\Enrollment;

class SectionNotAvailableException extends \RuntimeException
{
    public function __construct(string $reason = '')
    {
        parent::__construct($reason ?: 'La sección no está disponible para matrículas.');
    }
}
