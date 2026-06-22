<?php

namespace App\Http\Controllers\Alumno;

use App\Academic\Attendance;
use App\Academic\ClassSession;
use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Services\Lms\MaterialService;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function show(CourseSection $courseSection, MaterialService $materialService): View
    {
        $alumnoId = auth()->id();

        $enrollment = Enrollment::where('alumno_id', $alumnoId)
            ->where('course_section_id', $courseSection->id)
            ->where('status', 'activa')
            ->first();

        if (! $enrollment) {
            abort(403);
        }

        $courseSection->load([
            'course.program',
            'academicPeriod',
            'teachers',
            'schedules',
        ]);

        $enrollments = Enrollment::with('alumno')
            ->where('course_section_id', $courseSection->id)
            ->whereIn('status', ['activa', 'suspendida', 'retirada'])
            ->orderBy('status')
            ->get();

        $sectionMaterials = $materialService->getForSection($courseSection);

        $myAttendances = Attendance::with('classSession')
            ->where('enrollment_id', $enrollment->id)
            ->whereHas('classSession', fn ($q) => $q->where('course_section_id', $courseSection->id))
            ->get();

        $totalSessions = ClassSession::where('course_section_id', $courseSection->id)
            ->where('status', 'completed')
            ->count();

        $teacher = $courseSection->primaryTeacher();

        return view('alumno.sections.show', compact(
            'courseSection',
            'enrollments',
            'sectionMaterials',
            'myAttendances',
            'totalSessions',
            'teacher',
        ));
    }
}
