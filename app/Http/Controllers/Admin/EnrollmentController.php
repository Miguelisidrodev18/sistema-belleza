<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EnrollmentStatus;
use App\Exceptions\Enrollment\AlreadyEnrolledException;
use App\Exceptions\Enrollment\InvalidStudentException;
use App\Exceptions\Enrollment\SectionFullException;
use App\Exceptions\Enrollment\SectionNotAvailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEnrollmentRequest;
use App\Http\Requests\Admin\UpdateEnrollmentRequest;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\User;
use App\Services\Enrollment\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(protected EnrollmentService $service) {}

    public function index(Request $request): View
    {
        $query = Enrollment::with([
            'alumno',
            'courseSection.course.program',
            'courseSection.academicPeriod',
            'academicPeriod',
        ]);

        if ($request->filled('period_id')) {
            $query->where('academic_period_id', $request->period_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('enrollment_number', 'like', "%{$search}%")
                    ->orWhereHas('alumno', fn($q) => $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $enrollments = $query->latest()->paginate(20)->withQueryString();
        $periods     = AcademicPeriod::orderByDesc('start_date')->get();

        return view('admin.enrollments.index', compact('enrollments', 'periods'));
    }

    public function create(): View
    {
        $alumnos  = User::alumnos()->active()->orderBy('name')->get();
        $sections = CourseSection::with(['course.program', 'academicPeriod'])
            ->where('status', 'published')
            ->where('is_active', true)
            ->whereHas('academicPeriod', fn($q) => $q->where('status', '!=', 'finalizado'))
            ->get()
            ->groupBy(fn($s) => $s->course->program->name);

        $capacities = CourseSection::with([])
            ->where('status', 'published')
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(fn($s) => [
                $s->id => [
                    'enrolled'  => $s->enrolledCount(),
                    'capacity'  => $s->capacity,
                    'available' => $s->available_slots,
                ],
            ]);

        return view('admin.enrollments.create', compact('alumnos', 'sections', 'capacities'));
    }

    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        $alumno  = User::findOrFail($request->alumno_id);
        $section = CourseSection::with('academicPeriod')->findOrFail($request->course_section_id);

        try {
            $enrollment = $this->service->enroll($alumno, $section, ['remarks' => $request->remarks]);

            return redirect()->route('admin.enrollments.index')
                ->with('success', "Matrícula {$enrollment->enrollment_number} registrada correctamente.");
        } catch (SectionFullException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (AlreadyEnrolledException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (InvalidStudentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (SectionNotAvailableException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Enrollment $enrollment): View
    {
        $enrollment->load(['alumno', 'courseSection.course.program', 'academicPeriod', 'activities.performedBy']);

        return view('admin.enrollments.edit', compact('enrollment'));
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $newStatus = EnrollmentStatus::from($request->status);
        $this->service->updateStatus($enrollment, $newStatus);

        if ($request->filled('remarks')) {
            $enrollment->update(['remarks' => $request->remarks]);
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Estado de la matrícula {$enrollment->enrollment_number} actualizado.");
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $number = $enrollment->enrollment_number;
        $this->service->withdraw($enrollment);

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Matrícula {$number} marcada como retirada.");
    }
}
