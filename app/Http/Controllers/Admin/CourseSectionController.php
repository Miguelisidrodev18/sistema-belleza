<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseSectionRequest;
use App\Http\Requests\Admin\UpdateCourseSectionRequest;
use App\Lms\Material;
use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Services\Academic\CourseSectionService;

class CourseSectionController extends Controller
{
    public function __construct(
        private CourseSectionService $service,
    ) {}

    public function index()
    {
        $currentPeriod = view()->shared('currentPeriod');
        $selectedPeriodId = request('period_id', $currentPeriod?->id);

        $sections = CourseSection::with(['course.program', 'academicPeriod', 'teachers'])
            ->when($selectedPeriodId, fn($q) => $q->where('academic_period_id', $selectedPeriodId))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $periods = AcademicPeriod::orderByDesc('start_date')->get();

        return view('admin.course-sections.index', compact('sections', 'periods', 'selectedPeriodId'));
    }

    public function create()
    {
        $courses   = Course::with('program')->active()->orderBy('name')->get();
        $periods   = AcademicPeriod::orderByDesc('start_date')->get();
        $teachers  = $this->service->getAvailableTeachers();

        return view('admin.course-sections.create', compact('courses', 'periods', 'teachers'));
    }

    public function store(StoreCourseSectionRequest $request)
    {
        $data = $request->validated();
        $teacherId = $data['teacher_id'] ?? null;
        $teacherRole = $data['teacher_role'] ?? 'principal';

        $section = $this->service->create([
            'course_id'          => $data['course_id'],
            'academic_period_id' => $data['academic_period_id'],
            'section_code'       => $data['section_code'],
            'capacity'           => $data['capacity'],
            'status'             => $data['status'],
            'is_active'          => $data['is_active'] ?? true,
        ]);

        if ($teacherId) {
            $this->service->assignTeacher($section, (int) $teacherId, $teacherRole, true);
        }

        return redirect()->route('admin.course-sections.index')
            ->with('success', 'Sección académica creada correctamente.');
    }

    public function show(CourseSection $courseSection)
    {
        $courseSection->load([
            'course.program',
            'academicPeriod',
            'teachers',
            'schedules',
        ]);

        $enrollments = Enrollment::with('alumno')
            ->where('course_section_id', $courseSection->id)
            ->where('status', 'activa')
            ->get();

        $materials = Material::with(['currentVersion.attachments', 'createdBy'])
            ->forSection($courseSection->id)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        $activeTab = request('tab', 'general');

        return view('admin.course-sections.show', compact(
            'courseSection', 'enrollments', 'materials', 'activeTab'
        ));
    }

    public function edit(CourseSection $courseSection)
    {
        $courseSection->load(['course.program', 'academicPeriod', 'teachers']);

        $courses   = Course::with('program')->active()->orderBy('name')->get();
        $periods   = AcademicPeriod::orderByDesc('start_date')->get();
        $teachers  = $this->service->getAvailableTeachers();
        $primaryTeacher = $courseSection->primaryTeacher();

        return view('admin.course-sections.edit', compact('courseSection', 'courses', 'periods', 'teachers', 'primaryTeacher'));
    }

    public function update(UpdateCourseSectionRequest $request, CourseSection $courseSection)
    {
        $data = $request->validated();
        $teacherId = $data['teacher_id'] ?? null;
        $teacherRole = $data['teacher_role'] ?? 'principal';

        $this->service->update($courseSection, [
            'course_id'          => $data['course_id'],
            'academic_period_id' => $data['academic_period_id'],
            'section_code'       => $data['section_code'],
            'capacity'           => $data['capacity'],
            'status'             => $data['status'],
            'is_active'          => $data['is_active'] ?? true,
        ]);

        if ($teacherId) {
            $courseSection->teachers()->detach();
            $this->service->assignTeacher($courseSection, (int) $teacherId, $teacherRole, true);
        }

        return redirect()->route('admin.course-sections.index')
            ->with('success', 'Sección académica actualizada correctamente.');
    }

    public function destroy(CourseSection $courseSection)
    {
        $courseSection->delete();

        return redirect()->route('admin.course-sections.index')
            ->with('success', 'Sección académica eliminada.');
    }
}
