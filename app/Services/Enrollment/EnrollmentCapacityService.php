<?php

namespace App\Services\Enrollment;

use App\Exceptions\Enrollment\SectionFullException;
use App\Models\CourseSection;

class EnrollmentCapacityService
{
    public function getEnrolledCount(CourseSection $section): int
    {
        return $section->enrollments()->where('status', 'activa')->count();
    }

    public function hasAvailableSlots(CourseSection $section): bool
    {
        return $this->getEnrolledCount($section) < $section->capacity;
    }

    /**
     * Must be called inside a DB::transaction AFTER lockForUpdate() on the section.
     */
    public function checkCapacity(CourseSection $section): void
    {
        $enrolled = $section->enrollments()->where('status', 'activa')->count();

        if ($enrolled >= $section->capacity) {
            throw new SectionFullException();
        }
    }
}
