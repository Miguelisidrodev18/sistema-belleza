<?php

namespace App\Services\Academic;

use App\Enums\AcademicPeriodStatus;
use App\Models\AcademicPeriod;

class AcademicPeriodService
{
    public function __construct(
        private AcademicSettingsService $settingsService,
    ) {}

    public function create(array $data): AcademicPeriod
    {
        return AcademicPeriod::create($data);
    }

    public function update(AcademicPeriod $period, array $data): AcademicPeriod
    {
        $period->update($data);
        return $period->fresh();
    }

    public function setAsCurrent(AcademicPeriod $period): void
    {
        AcademicPeriod::where('is_current', true)->update(['is_current' => false]);
        $period->update(['is_current' => true]);
        $this->settingsService->setCurrentPeriod($period);
    }

    public function getCurrentPeriod(): ?AcademicPeriod
    {
        return AcademicPeriod::current()->first()
            ?? AcademicPeriod::orderByDesc('start_date')->first();
    }

    public function validateNoCycle(AcademicPeriod $period, int $previousId): bool
    {
        if ($period->id === $previousId) {
            return false;
        }

        $visited = [$period->id];
        $current = AcademicPeriod::find($previousId);

        while ($current !== null && $current->previous_period_id !== null) {
            if (in_array($current->id, $visited)) {
                return false;
            }
            $visited[] = $current->id;
            $current = AcademicPeriod::find($current->previous_period_id);
        }

        return true;
    }
}
