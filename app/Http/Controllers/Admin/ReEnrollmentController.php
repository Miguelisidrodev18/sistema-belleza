<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReEnrollmentExecuteRequest;
use App\Http\Requests\Admin\ReEnrollmentPreviewRequest;
use App\Models\AcademicPeriod;
use App\Services\Enrollment\ReEnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReEnrollmentController extends Controller
{
    public function __construct(protected ReEnrollmentService $service) {}

    public function index(Request $request): View
    {
        $periods = AcademicPeriod::orderByDesc('start_date')->get();
        $currentPeriod = AcademicPeriod::where('is_current', true)->first();
        $previousPeriod = $currentPeriod?->previousPeriod;

        return view('admin.re-enrollment.index', compact('periods', 'currentPeriod', 'previousPeriod'));
    }

    public function preview(ReEnrollmentPreviewRequest $request): View
    {
        $source = AcademicPeriod::findOrFail($request->source_period_id);
        $target = AcademicPeriod::findOrFail($request->target_period_id);
        $periods = AcademicPeriod::orderByDesc('start_date')->get();

        $eligible = $this->service->getEligibleStudents($source, $target);

        return view('admin.re-enrollment.preview', compact('source', 'target', 'eligible', 'periods'));
    }

    public function execute(ReEnrollmentExecuteRequest $request): RedirectResponse
    {
        $target  = AcademicPeriod::findOrFail($request->target_period_id);
        $results = $this->service->executeReEnrollment(
            $request->enrollment_ids,
            $target,
            auth()->user()
        );

        $message = "Re-matrícula completada: {$results['enrolled']} matriculados";
        if ($results['skipped'] > 0) {
            $message .= ", {$results['skipped']} omitidos";
        }

        return redirect()->route('admin.enrollments.index')
            ->with('success', $message . '.');
    }
}
