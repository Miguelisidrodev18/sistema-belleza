<?php

namespace App\Services\Academic;

use App\Models\AcademicPeriod;
use App\Models\AcademicSettings;

class AcademicSettingsService
{
    public function get(): AcademicSettings
    {
        return AcademicSettings::get();
    }

    public function setCurrentPeriod(AcademicPeriod $period): void
    {
        $settings = $this->get();
        $settings->update(['current_period_id' => $period->id]);
    }

    public function getDefaultCapacity(): int
    {
        return $this->get()->default_capacity;
    }
}
