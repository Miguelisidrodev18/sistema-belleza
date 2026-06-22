<?php

namespace App\Http\Middleware;

use App\Services\Academic\AcademicPeriodService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetActiveAcademicPeriod
{
    public function __construct(
        private AcademicPeriodService $periodService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        View::share('currentPeriod', $this->periodService->getCurrentPeriod());

        return $next($request);
    }
}
