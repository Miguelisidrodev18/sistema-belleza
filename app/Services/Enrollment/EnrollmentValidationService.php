<?php

namespace App\Services\Enrollment;

use App\Enums\AcademicPeriodStatus;
use App\Enums\CourseSectionStatus;
use App\Exceptions\Enrollment\AlreadyEnrolledException;
use App\Exceptions\Enrollment\InvalidStudentException;
use App\Exceptions\Enrollment\SectionNotAvailableException;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;

class EnrollmentValidationService
{
    public function validateAlumno(User $user): void
    {
        if ($user->role !== 'alumno' || ! $user->is_active) {
            throw new InvalidStudentException();
        }
    }

    public function validateSection(CourseSection $section): void
    {
        if (! $section->is_active) {
            throw new SectionNotAvailableException('La sección no está activa.');
        }

        if ($section->status !== CourseSectionStatus::Published) {
            throw new SectionNotAvailableException(
                'La sección no está disponible para matrículas (estado: ' . $section->status->label() . ').'
            );
        }

        $period = $section->academicPeriod;
        if ($period && $period->status === AcademicPeriodStatus::Finalizado) {
            throw new SectionNotAvailableException('No se puede matricular en un período académico finalizado.');
        }
    }

    public function validateNotAlreadyEnrolled(User $alumno, CourseSection $section): void
    {
        if (Enrollment::where('alumno_id', $alumno->id)
            ->where('course_section_id', $section->id)
            ->exists()) {
            throw new AlreadyEnrolledException();
        }
    }

    public function validateAll(User $alumno, CourseSection $section): void
    {
        $this->validateAlumno($alumno);
        $section->loadMissing('academicPeriod');
        $this->validateSection($section);
        $this->validateNotAlreadyEnrolled($alumno, $section);
    }
}
