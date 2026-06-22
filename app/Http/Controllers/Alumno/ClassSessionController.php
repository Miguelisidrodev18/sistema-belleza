<?php

namespace App\Http\Controllers\Alumno;

use App\Academic\Attendance;
use App\Academic\ClassSession;
use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\Lms\MaterialService;
use Illuminate\View\View;

class ClassSessionController extends Controller
{
    public function show(ClassSession $classSession, MaterialService $materialService): View
    {
        $alumnoId = auth()->id();

        $hasEnrollment = Enrollment::where('alumno_id', $alumnoId)
            ->where('course_section_id', $classSession->course_section_id)
            ->where('status', 'activa')
            ->exists();

        if (! $hasEnrollment) {
            abort(403);
        }

        $classSession->load([
            'courseSection.course.program',
            'courseSection.teachers',
            'courseSection.academicPeriod',
            'courseSection.schedules',
            'meeting',
        ]);

        $enrollments = Enrollment::with('alumno')
            ->where('course_section_id', $classSession->course_section_id)
            ->whereIn('status', ['activa', 'suspendida', 'retirada'])
            ->orderBy('status')
            ->get();

        $sessionMaterials = $materialService->getForSession($classSession);
        $sectionMaterials = $materialService->getForSection($classSession->courseSection);

        $enrollment = Enrollment::where('alumno_id', $alumnoId)
            ->where('course_section_id', $classSession->course_section_id)
            ->first();

        $myAttendances = Attendance::with('classSession')
            ->where('enrollment_id', $enrollment->id)
            ->whereHas('classSession', fn ($q) => $q->where('course_section_id', $classSession->course_section_id))
            ->get();

        $totalSessions = ClassSession::where('course_section_id', $classSession->course_section_id)
            ->where('status', 'completed')
            ->count();

        return view('alumno.class-sessions.show', compact(
            'classSession',
            'enrollments',
            'sessionMaterials',
            'sectionMaterials',
            'myAttendances',
            'totalSessions',
        ));
    }
}
