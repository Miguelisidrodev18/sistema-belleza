<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAcademicPeriodRequest;
use App\Http\Requests\Admin\UpdateAcademicPeriodRequest;
use App\Models\AcademicPeriod;
use App\Services\Academic\AcademicPeriodService;

class AcademicPeriodController extends Controller
{
    public function __construct(
        private AcademicPeriodService $service,
    ) {}

    public function index()
    {
        $periods = AcademicPeriod::with('previousPeriod')
            ->orderByDesc('start_date')
            ->paginate(15);

        return view('admin.academic-periods.index', compact('periods'));
    }

    public function create()
    {
        $previousOptions = AcademicPeriod::orderByDesc('start_date')->get();
        return view('admin.academic-periods.create', compact('previousOptions'));
    }

    public function store(StoreAcademicPeriodRequest $request)
    {
        $data = $request->validated();

        if (isset($data['previous_period_id']) && $data['previous_period_id']) {
            // Dummy period for creation — no cycle possible yet
        }

        $this->service->create($data);

        return redirect()->route('admin.academic-periods.index')
            ->with('success', 'Período académico creado correctamente.');
    }

    public function edit(AcademicPeriod $academicPeriod)
    {
        $previousOptions = AcademicPeriod::where('id', '!=', $academicPeriod->id)
            ->orderByDesc('start_date')
            ->get();

        return view('admin.academic-periods.edit', [
            'period'          => $academicPeriod,
            'previousOptions' => $previousOptions,
        ]);
    }

    public function update(UpdateAcademicPeriodRequest $request, AcademicPeriod $academicPeriod)
    {
        $data = $request->validated();

        if (
            isset($data['previous_period_id']) &&
            $data['previous_period_id'] &&
            ! $this->service->validateNoCycle($academicPeriod, (int) $data['previous_period_id'])
        ) {
            return back()->withErrors(['previous_period_id' => 'La asignación de período anterior crearía un ciclo.']);
        }

        $this->service->update($academicPeriod, $data);

        return redirect()->route('admin.academic-periods.index')
            ->with('success', 'Período académico actualizado correctamente.');
    }

    public function destroy(AcademicPeriod $academicPeriod)
    {
        if ($academicPeriod->is_current) {
            return back()->with('error', 'No se puede eliminar el período activo.');
        }

        $academicPeriod->delete();

        return redirect()->route('admin.academic-periods.index')
            ->with('success', 'Período académico eliminado.');
    }

    public function setCurrent(AcademicPeriod $academicPeriod)
    {
        $this->service->setAsCurrent($academicPeriod);

        return back()->with('success', "«{$academicPeriod->name}» es ahora el período actual.");
    }
}
