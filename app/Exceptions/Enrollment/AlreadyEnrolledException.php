<?php

namespace App\Exceptions\Enrollment;

class AlreadyEnrolledException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('El alumno ya está matriculado en esta sección.');
    }
}
