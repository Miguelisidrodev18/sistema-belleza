<?php

namespace App\Http\Controllers\Alumno;

use App\Academic\ClassSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('alumno.calendar.index');
    }

    public function sessions(Request $request): JsonResponse
    {
        $month       = $request->input('month', now()->format('Y-m'));
        $from        = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
        $to          = $from->copy()->endOfMonth();
        $alumnoId    = auth()->id();

        // Find enrollment IDs for this alumno
        $enrollmentIds = \App\Models\Enrollment::where('alumno_id', $alumnoId)
            ->where('status', 'activa')
            ->pluck('course_section_id');

        $sessions = ClassSession::with(['courseSection.course.program', 'meeting', 'attendances'])
            ->whereIn('course_section_id', $enrollmentIds)
            ->whereBetween('starts_at', [$from, $to])
            ->orderBy('starts_at')
            ->get();

        $data = $sessions->map(function (ClassSession $session) use ($alumnoId) {
            $meeting     = $session->meeting;
            $isToday     = $session->starts_at->isToday();
            $isLive      = $session->starts_at->lte(now()) && $session->ends_at->gte(now())
                           && $meeting?->status === 'live';
            $canJoin     = $isLive && ! empty($meeting?->meeting_url);
            $atTaken     = $session->attendances->isNotEmpty();

            return [
                'id'               => $session->id,
                'title'            => $session->title ?? "Clase #{$session->session_number}",
                'starts_at'        => $session->starts_at->toIso8601String(),
                'ends_at'          => $session->ends_at->toIso8601String(),
                'course'           => $session->courseSection->course->name,
                'program'          => $session->courseSection->course->program->name,
                'color'            => $session->courseSection->course->program->color ?? '#6366f1',
                'status'           => $session->status->value,
                'meeting_url'      => $meeting?->meeting_url,
                'room'             => $session->effectiveRoom,
                'is_today'         => $isToday,
                'is_live'          => $isLive,
                'can_join'         => $canJoin,
                'attendance_taken' => $atTaken,
                'show_url'         => route('alumno.class-sessions.show', $session),
            ];
        });

        return response()->json($data);
    }
}
