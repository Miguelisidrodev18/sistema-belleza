<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    public function run(): void
    {
        $prev = AcademicPeriod::create([
            'name'               => '2025-II',
            'start_date'         => '2025-07-01',
            'end_date'           => '2025-12-31',
            'status'             => 'finalizado',
            'is_current'         => false,
            'previous_period_id' => null,
        ]);

        AcademicPeriod::create([
            'name'               => '2026-I',
            'start_date'         => '2026-01-15',
            'end_date'           => '2026-06-30',
            'status'             => 'activo',
            'is_current'         => true,
            'previous_period_id' => $prev->id,
        ]);
    }
}
