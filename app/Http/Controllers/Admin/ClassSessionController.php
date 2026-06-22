<?php

namespace App\Http\Controllers\Admin;

use App\Academic\ClassSession;
use App\Academic\Meeting;
use App\Enums\ClassSessionStatus;
use App\Enums\MeetingPlatform;
use App\Enums\ScheduleModality;
use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSessionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ClassSession::with(['courseSection.course.program', 'courseSection.academicPeriod'])
            ->orderBy('starts_at', 'desc');

        if ($request->filled('section')) {
            $query->where('course_section_id', $request->section);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('starts_at', $request->date);
        }

        $sessions  = $query->paginate(30)->withQueryString();
        $statuses  = ClassSessionStatus::cases();
        $sections  = CourseSection::with('course')->orderBy('id')->get();

        return view('admin.class-sessions.index', compact('sessions', 'statuses', 'sections'));
    }

    public function create(): View
    {
        $sections   = CourseSection::with(['course.program', 'academicPeriod'])->orderBy('id')->get();
        $statuses   = ClassSessionStatus::cases();
        $modalities = ScheduleModality::cases();

        return view('admin.class-sessions.create', compact('sections', 'statuses', 'modalities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_section_id' => 'required|exists:course_sections,id',
            'starts_at'         => 'required|date',
            'ends_at'           => 'required|date|after:starts_at',
            'title'             => 'nullable|string|max:200',
            'room'              => 'nullable|string|max:100',
            'modality'          => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        $data['is_generated']   = false;
        $data['status']         = 'scheduled';
        $data['session_number'] = ClassSession::where('course_section_id', $data['course_section_id'])->max('session_number') + 1;

        $session = ClassSession::create($data);

        // Meeting optional
        if ($request->filled('meeting_url')) {
            $session->meeting()->create([
                'platform'    => $request->input('meeting_platform', 'zoom'),
                'meeting_url' => $request->input('meeting_url'),
                'meeting_id'  => $request->input('meeting_id'),
                'passcode'    => $request->input('passcode'),
                'host_url'    => $request->input('host_url'),
                'status'      => 'pending',
            ]);
        }

        return redirect()->route('admin.class-sessions.index')
            ->with('success', "Sesión #{$session->session_number} creada.");
    }

    public function edit(ClassSession $classSession): View
    {
        $classSession->load(['courseSection.course', 'schedule', 'meeting']);
        $statuses   = ClassSessionStatus::cases();
        $modalities = ScheduleModality::cases();
        $platforms  = MeetingPlatform::cases();

        return view('admin.class-sessions.edit', compact('classSession', 'statuses', 'modalities', 'platforms'));
    }

    public function update(Request $request, ClassSession $classSession): RedirectResponse
    {
        $data = $request->validate([
            'starts_at'        => 'required|date',
            'ends_at'          => 'required|date|after:starts_at',
            'title'            => 'nullable|string|max:200',
            'room'             => 'nullable|string|max:100',
            'modality'         => 'nullable|string',
            'status'           => 'required|string',
            'notes'            => 'nullable|string',
            'cancelled_reason' => 'nullable|string',
        ]);

        $classSession->update($data);

        // Upsert Meeting
        if ($request->filled('meeting_url')) {
            $classSession->meeting()->updateOrCreate(
                ['class_session_id' => $classSession->id],
                [
                    'platform'    => $request->input('meeting_platform', 'zoom'),
                    'meeting_url' => $request->input('meeting_url'),
                    'meeting_id'  => $request->input('meeting_id'),
                    'passcode'    => $request->input('passcode'),
                    'host_url'    => $request->input('host_url'),
                    'recording_url'      => $request->input('recording_url'),
                    'status'      => $request->input('meeting_status', 'pending'),
                ]
            );
        }

        return redirect()->route('admin.class-sessions.index')
            ->with('success', 'Sesión actualizada.');
    }

    public function destroy(ClassSession $classSession): RedirectResponse
    {
        $classSession->delete();

        return redirect()->route('admin.class-sessions.index')
            ->with('success', 'Sesión eliminada.');
    }
}
