<?php

namespace App\Exceptions\Enrollment;

class InvalidStudentException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('El usuario no es un alumno activo.');
    }
}
