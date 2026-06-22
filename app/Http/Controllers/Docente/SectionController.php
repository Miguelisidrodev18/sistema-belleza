<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        $sections = CourseSection::with(['course.program', 'academicPeriod'])
            ->whereHas('teachers', fn($q) => $q->where('teacher_id', auth()->id()))
            ->whereHas('academicPeriod', fn($q) => $q->where('is_current', true))
            ->orderBy('id')
            ->get();

        return view('docente.sections.index', compact('sections'));
    }

    public function students(CourseSection $courseSection): View
    {
        // Verify the docente teaches this section
        $isMine = $courseSection->teachers()->wherePivot('teacher_id', auth()->id())->exists();
        if (! $isMine) {
            abort(403);
        }

        $courseSection->load([
            'course.program',
            'academicPeriod',
            'enrollments.alumno',
        ]);

        $enrollments = $courseSection->enrollments()
            ->with('alumno')
            ->orderBy('enrolled_at')
            ->get();

        return view('docente.sections.show', compact('courseSection', 'enrollments'));
    }
}
