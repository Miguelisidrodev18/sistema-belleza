<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Services\Academic\ClassSessionGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SessionGeneratorController extends Controller
{
    public function __construct(protected ClassSessionGeneratorService $generator) {}

    public function index(): View
    {
        $sections = CourseSection::with(['course.program', 'academicPeriod'])
            ->whereHas('academicPeriod', fn($q) => $q->where('is_current', true))
            ->orderBy('id')
            ->get();

        $currentPeriod = AcademicPeriod::where('is_current', true)->first();

        return view('admin.session-generator.index', compact('sections', 'currentPeriod'));
    }

    public function preview(Request $request): View
    {
        $data = $request->validate([
            'course_section_id' => 'required|exists:course_sections,id',
            'from'              => 'required|date',
            'to'                => 'required|date|after_or_equal:from',
            'exclude_dates'     => 'nullable|array',
            'exclude_dates.*'   => 'date',
        ]);

        $section       = CourseSection::with(['course.program', 'schedules'])->findOrFail($data['course_section_id']);
        $from          = Carbon::parse($data['from']);
        $to            = Carbon::parse($data['to']);
        $excludeDates  = $data['exclude_dates'] ?? [];

        $candidates = $this->generator->preview($section, $from, $to, $excludeDates);
        $conflicts  = $candidates->filter(fn($c) => $c->has_conflict)->count();

        return view('admin.session-generator.preview', compact(
            'section', 'from', 'to', 'excludeDates', 'candidates', 'conflicts'
        ));
    }

    public function simulate(Request $request): View
    {
        $data = $request->validate([
            'course_section_id' => 'required|exists:course_sections,id',
            'from'              => 'required|date',
            'to'                => 'required|date',
            'exclude_dates'     => 'nullable|array',
            'excluded_indices'  => 'nullable|array',
            'overrides'         => 'nullable|array',
        ]);

        $section          = CourseSection::with(['course.program', 'schedules'])->findOrFail($data['course_section_id']);
        $from             = Carbon::parse($data['from']);
        $to               = Carbon::parse($data['to']);
        $excludeDates     = $data['exclude_dates'] ?? [];
        $excludedIndices  = array_map('intval', $data['excluded_indices'] ?? []);
        $overrides        = $data['overrides'] ?? [];

        $candidates        = $this->generator->preview($section, $from, $to, $excludeDates);
        $toCreate          = $candidates->count() - count($excludedIndices);
        $conflictsDetected = $candidates->filter(fn($c) => $c->has_conflict)->count();
        $conflictsIgnored  = $candidates->filter(function ($c) use ($overrides) {
            $ov = $overrides[$c->index] ?? [];
            return $c->has_conflict && (! empty($ov['ignore_conflict']));
        })->count();
        $roomOverrides = collect($overrides)->filter(fn($ov) => ! empty($ov['room']))->count();

        return view('admin.session-generator.simulate', compact(
            'section', 'from', 'to', 'excludeDates', 'excludedIndices', 'overrides',
            'toCreate', 'conflictsDetected', 'conflictsIgnored', 'roomOverrides'
        ));
    }

    public function generate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_section_id' => 'required|exists:course_sections,id',
            'from'              => 'required|date',
            'to'                => 'required|date',
            'exclude_dates'     => 'nullable|array',
            'excluded_indices'  => 'nullable|array',
            'overrides'         => 'nullable|array',
        ]);

        $section          = CourseSection::with('schedules')->findOrFail($data['course_section_id']);
        $from             = Carbon::parse($data['from']);
        $to               = Carbon::parse($data['to']);
        $excludeDates     = $data['exclude_dates'] ?? [];
        $excludedIndices  = array_map('intval', $data['excluded_indices'] ?? []);
        $overrides        = $data['overrides'] ?? [];

        $results = $this->generator->generate($section, $from, $to, $excludeDates, $overrides, $excludedIndices);

        $msg = "{$results['created']} sesiones creadas";
        if ($results['skipped'] > 0)           { $msg .= " · {$results['skipped']} omitidas"; }
        if ($results['conflicts_ignored'] > 0) { $msg .= " · {$results['conflicts_ignored']} conflictos ignorados"; }

        return redirect()->route('admin.class-sessions.index')
            ->with('success', $msg);
    }
}
