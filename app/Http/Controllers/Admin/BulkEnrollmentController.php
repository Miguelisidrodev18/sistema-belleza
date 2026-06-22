<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkEnrollmentRequest;
use App\Models\CourseSection;
use App\Models\User;
use App\Services\Enrollment\BulkEnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BulkEnrollmentController extends Controller
{
    public function __construct(protected BulkEnrollmentService $bulkService) {}

    public function index(): View
    {
        return view('admin.enrollments.bulk');
    }

    public function sections(Request $request): JsonResponse
    {
        $search = trim($request->input('search', ''));

        $sections = CourseSection::with(['course.program', 'academicPeriod'])
            ->where('is_active', true)
            ->where('status', 'published')
            ->whereHas('academicPeriod', fn ($q) => $q->where('status', '!=', 'finalizado'))
            ->withCount(['enrollments as enrolled_count' => fn ($q) => $q->where('status', 'activa')])
            ->when($search, fn ($q) => $q->whereHas('course', fn ($q2) => $q2->where('name', 'like', "%{$search}%")
                ->orWhereHas('program', fn ($q3) => $q3->where('name', 'like', "%{$search}%"))))
            ->get()
            ->filter(fn ($s) => $s->enrolled_count < $s->capacity)
            ->values()
            ->map(fn (CourseSection $s) => [
                'id'              => $s->id,
                'course_name'     => $s->course->name,
                'section_code'    => $s->section_code,
                'program_name'    => $s->course->program->name,
                'period_name'     => $s->academicPeriod->name,
                'teacher_name'    => $s->primaryTeacher()?->name ?? 'Sin asignar',
                'capacity'        => $s->capacity,
                'enrolled_count'  => $s->enrolled_count,
                'available_slots' => $s->capacity - $s->enrolled_count,
            ]);

        return response()->json($sections);
    }

    public function students(Request $request): JsonResponse
    {
        $request->validate([
            'course_section_id' => ['required', 'integer', 'exists:course_sections,id'],
        ]);

        $sectionId = $request->integer('course_section_id');
        $search = trim($request->input('search', ''));

        $students = User::alumnos()->active()
            ->whereNotIn('id', function ($q) use ($sectionId) {
                $q->select('alumno_id')
                    ->from('enrollments')
                    ->where('course_section_id', $sectionId);
            })
            ->withCount(['enrollments as active_enrollments_count' => fn ($q) => $q->where('status', 'activa')])
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get()
            ->map(fn (User $u) => [
                'id'                   => $u->id,
                'name'                 => $u->name,
                'dni'                  => $u->dni,
                'email'                => $u->email,
                'active_enrollments'   => $u->active_enrollments_count,
            ]);

        return response()->json([
            'students' => $students,
            'total'    => $students->count(),
        ]);
    }

    public function execute(BulkEnrollmentRequest $request): JsonResponse
    {
        $section = CourseSection::findOrFail($request->input('course_section_id'));
        $results = $this->bulkService->enrollMany(
            $section,
            $request->input('alumno_ids'),
            $request->input('remarks'),
        );

        return response()->json([
            'success'      => $results['enrolled'] > 0,
            'enrolled'     => $results['enrolled'],
            'skipped'      => $results['skipped'],
            'errors'       => $results['errors'],
            'redirect_url' => route('admin.enrollments.index'),
        ]);
    }
}
