<?php

namespace App\Http\Controllers\Docente;

use App\Academic\ClassSession;
use App\Enums\ClassSessionStatus;
use App\Http\Controllers\Controller;
use App\Services\Academic\AttendanceService;
use App\Services\Lms\MaterialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSessionController extends Controller
{
    public function __construct(
        protected AttendanceService $attendance,
        protected MaterialService $materials,
    ) {}

    public function index(): View
    {
        $teacherId = auth()->id();

        $baseQuery = ClassSession::with(['courseSection.course.program', 'courseSection.academicPeriod', 'meeting'])
            ->whereHas('courseSection', function ($q) use ($teacherId) {
                $q->whereHas('teachers', fn($t) => $t->where('users.id', $teacherId)->where('course_section_teachers.is_primary', true))
                  ->whereHas('academicPeriod', fn($p) => $p->where('is_current', true));
            });

        $today    = (clone $baseQuery)->today()->orderBy('starts_at')->get();
        $upcoming = (clone $baseQuery)->where('starts_at', '>', now())->whereDate('starts_at', '!=', today())
                        ->orderBy('starts_at')->limit(20)->get();
        $past     = (clone $baseQuery)->where('ends_at', '<', now())->whereDate('starts_at', '!=', today())
                        ->orderBy('starts_at', 'desc')->limit(20)->get();

        return view('docente.class-sessions.index', compact('today', 'upcoming', 'past'));
    }

    public function show(ClassSession $classSession): View
    {
        $this->authorizeMine($classSession);

        $classSession->load([
            'courseSection.course.program',
            'courseSection.academicPeriod',
            'schedule',
            'meeting',
            'attendances.enrollment.alumno',
        ]);

        // Active enrollments for the section
        $enrollments = $classSession->courseSection->enrollments()
            ->with('alumno')
            ->where('status', 'activa')
            ->orderBy('enrollment_number')
            ->get();

        $stats           = $this->attendance->getSessionStats($classSession);
        $sessionMaterials = $this->materials->getForSession($classSession);

        return view('docente.class-sessions.show', compact('classSession', 'enrollments', 'stats', 'sessionMaterials'));
    }

    public function update(Request $request, ClassSession $classSession): RedirectResponse
    {
        $this->authorizeMine($classSession);

        $data = $request->validate([
            'status'           => 'required|string',
            'notes'            => 'nullable|string',
            'cancelled_reason' => 'nullable|string',
            'meeting_url'      => 'nullable|url',
        ]);

        $classSession->update([
            'status'           => $data['status'],
            'notes'            => $data['notes'] ?? null,
            'cancelled_reason' => $data['cancelled_reason'] ?? null,
        ]);

        if (! empty($data['meeting_url'])) {
            $classSession->meeting()->updateOrCreate(
                ['class_session_id' => $classSession->id],
                [
                    'platform'    => $request->input('meeting_platform', 'zoom'),
                    'meeting_url' => $data['meeting_url'],
                    'status'      => 'live',
                ]
            );
        }

        return back()->with('success', 'Sesión actualizada.');
    }

    public function attendance(Request $request, ClassSession $classSession): RedirectResponse
    {
        $this->authorizeMine($classSession);

        $request->validate([
            'attendance'                 => 'required|array',
            'attendance.*.status'        => 'required|string',
            'attendance.*.arrival_time'  => 'nullable|date_format:H:i',
            'attendance.*.departure_time'=> 'nullable|date_format:H:i',
            'attendance.*.notes'         => 'nullable|string',
        ]);

        $this->attendance->bulkRecord($classSession, $request->input('attendance'), auth()->user());

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    private function authorizeMine(ClassSession $session): void
    {
        $isMine = $session->courseSection()
            ->whereHas('teachers', fn($q) => $q->where('users.id', auth()->id())->where('course_section_teachers.is_primary', true))
            ->exists();
        if (! $isMine) {
            abort(403);
        }
    }
}
