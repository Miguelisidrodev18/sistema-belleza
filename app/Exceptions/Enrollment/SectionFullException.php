<?php

namespace App\Exceptions\Enrollment;

class SectionFullException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('La sección no tiene vacantes disponibles.');
    }
}
