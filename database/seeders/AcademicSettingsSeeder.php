<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\AcademicSettings;
use Illuminate\Database\Seeder;

class AcademicSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $currentPeriod = AcademicPeriod::where('is_current', true)->first();

        AcademicSettings::updateOrCreate(
            ['id' => 1],
            [
                'current_period_id'  => $currentPeriod?->id,
                'default_capacity'   => 30,
                'allow_overbooking'  => false,
                'default_timezone'   => 'America/Lima',
            ]
        );
    }
}
