<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgramRequest;
use App\Http\Requests\Admin\UpdateProgramRequest;
use App\Models\AcademicPeriod;
use App\Models\Program;
use App\Services\Academic\ProgramCompletenessService;
use App\Services\Academic\ProgramService;

class ProgramController extends Controller
{
    public function __construct(
        private ProgramService $service,
        private ProgramCompletenessService $completenessService,
    ) {}

    public function index()
    {
        $currentPeriod = view()->shared('currentPeriod');

        $programs = Program::withCount('courses')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (Program $program) use ($currentPeriod) {
                $program->completeness = $currentPeriod
                    ? $this->completenessService->getCompleteness($program, $currentPeriod)
                    : null;
                return $program;
            });

        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(StoreProgramRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.programs.index')
            ->with('success', 'Programa creado correctamente.');
    }

    public function show(Program $program)
    {
        $program->load(['courses' => fn($q) => $q->orderBy('sort_order')]);

        $currentPeriod = view()->shared('currentPeriod');
        $completeness = $currentPeriod
            ? $this->completenessService->getCompleteness($program, $currentPeriod)
            : null;

        $periods = AcademicPeriod::orderByDesc('start_date')->get();

        $sections = $program->courseSections()
            ->with(['course', 'academicPeriod', 'teachers'])
            ->when($currentPeriod, fn($q) => $q->where('academic_period_id', $currentPeriod->id))
            ->get();

        return view('admin.programs.show', compact('program', 'completeness', 'currentPeriod', 'periods', 'sections'));
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        $this->service->update($program, $request->validated());

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Programa actualizado correctamente.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Programa eliminado.');
    }
}
