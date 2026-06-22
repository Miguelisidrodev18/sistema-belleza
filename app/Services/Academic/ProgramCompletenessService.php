<?php

namespace App\Services\Academic;

use App\Models\AcademicPeriod;
use App\Models\Program;

class ProgramCompletenessService
{
    public function getCompleteness(Program $program, AcademicPeriod $period): array
    {
        $courses = $program->courses()->active()->get();
        $total = $courses->count();

        if ($total === 0) {
            return [
                'total_courses'  => 0,
                'with_section'   => 0,
                'with_teacher'   => 0,
                'percentage'     => 0,
                'is_complete'    => false,
            ];
        }

        $courseIds = $courses->pluck('id');

        $sectionsInPeriod = \App\Models\CourseSection::whereIn('course_id', $courseIds)
            ->where('academic_period_id', $period->id)
            ->get();

        $withSection = $sectionsInPeriod->pluck('course_id')->unique()->count();

        $withTeacher = $sectionsInPeriod->filter(
            fn($s) => $s->teachers()->wherePivot('is_primary', true)->exists()
        )->pluck('course_id')->unique()->count();

        $percentage = $total > 0 ? (int) round(($withTeacher / $total) * 100) : 0;

        return [
            'total_courses' => $total,
            'with_section'  => $withSection,
            'with_teacher'  => $withTeacher,
            'percentage'    => $percentage,
            'is_complete'   => $withTeacher === $total,
        ];
    }
}
