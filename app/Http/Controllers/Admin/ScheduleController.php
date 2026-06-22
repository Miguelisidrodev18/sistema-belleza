<?php

namespace App\Http\Controllers\Admin;

use App\Academic\Schedule;
use App\Enums\ScheduleModality;
use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(CourseSection $courseSection): View
    {
        $courseSection->load(['course.program', 'academicPeriod', 'schedules']);
        $schedules = $courseSection->schedules()->orderBy('day_of_week')->orderBy('start_time')->get();

        return view('admin.course-sections.schedules.index', compact('courseSection', 'schedules'));
    }

    public function create(CourseSection $courseSection): View
    {
        return view('admin.course-sections.schedules.create', [
            'courseSection' => $courseSection->load('course'),
            'modalities'    => ScheduleModality::cases(),
            'days'          => $this->dayOptions(),
        ]);
    }

    public function store(Request $request, CourseSection $courseSection): RedirectResponse
    {
        $data = $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'nullable|string|max:100',
            'modality'    => 'required|string',
            'notes'       => 'nullable|string',
        ]);

        $courseSection->schedules()->create($data);

        return redirect()->route('admin.course-sections.schedules.index', $courseSection)
            ->with('success', 'Horario creado correctamente.');
    }

    public function edit(CourseSection $courseSection, Schedule $schedule): View
    {
        return view('admin.course-sections.schedules.edit', [
            'courseSection' => $courseSection->load('course'),
            'schedule'      => $schedule,
            'modalities'    => ScheduleModality::cases(),
            'days'          => $this->dayOptions(),
        ]);
    }

    public function update(Request $request, CourseSection $courseSection, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'nullable|string|max:100',
            'modality'    => 'required|string',
            'is_active'   => 'boolean',
            'notes'       => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $schedule->update($data);

        return redirect()->route('admin.course-sections.schedules.index', $courseSection)
            ->with('success', 'Horario actualizado.');
    }

    public function destroy(CourseSection $courseSection, Schedule $schedule): RedirectResponse
    {
        if ($schedule->classSessions()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene sesiones generadas.');
        }

        $schedule->delete();

        return redirect()->route('admin.course-sections.schedules.index', $courseSection)
            ->with('success', 'Horario eliminado.');
    }

    private function dayOptions(): array
    {
        return [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
            4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
        ];
    }
}
