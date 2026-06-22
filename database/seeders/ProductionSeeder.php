<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\AcademicSettings;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@ugarteinstituto.edu.pe'],
            [
                'name'                 => 'Administrador Ugarte',
                'password'             => 'Ugarte2026*',   // ← cambia esto al entrar
                'role'                 => 'administrador',
                'is_active'            => true,
                'must_change_password' => true,            // fuerza cambio en primer login
            ]
        );

        // ── 2. Período académico actual ──────────────────────────────────
        $period = AcademicPeriod::firstOrCreate(
            ['name' => '2026-I'],
            [
                'start_date' => '2026-01-15',
                'end_date'   => '2026-12-31',
                'status'     => 'activo',
                'is_current' => true,
            ]
        );

        // Asegura que solo este período sea el actual
        AcademicPeriod::where('id', '!=', $period->id)
            ->update(['is_current' => false]);

        // ── 3. Configuración académica ───────────────────────────────────
        AcademicSettings::updateOrCreate(
            ['id' => 1],
            [
                'current_period_id' => $period->id,
                'default_capacity'  => 30,
                'allow_overbooking' => false,
                'default_timezone'  => 'America/Lima',
            ]
        );

        // ── 4. Programas y cursos (datos reales de Ugarte) ───────────────
        $this->call(ProgramSeeder::class);
    }
}
