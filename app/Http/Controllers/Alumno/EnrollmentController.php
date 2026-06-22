<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::with([
                'courseSection.course.program',
                'courseSection.teachers',
                'academicPeriod',
            ])
            ->where('alumno_id', auth()->id())
            ->whereHas('academicPeriod', fn($q) => $q->where('is_current', true))
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return view('alumno.enrollments.index', compact('enrollments'));
    }
}
