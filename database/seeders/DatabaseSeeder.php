<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        User::factory()->admin()->create([
            'name'  => 'Admin Ugarte',
            'email' => 'admin@ugarte.edu.pe',
            'dni'   => '00000001',
        ]);

        User::factory()->docente()->create([
            'name'  => 'Carlos Ríos García',
            'email' => 'carlos.rios@ugarte.edu.pe',
            'dni'   => '20456789',
        ]);

        User::factory()->docente()->create([
            'name'  => 'Juan Pérez Torres',
            'email' => 'juan.perez@ugarte.edu.pe',
            'dni'   => '30567890',
        ]);

        User::factory()->docente()->create([
            'name'  => 'María Torres Huamán',
            'email' => 'maria.torres@ugarte.edu.pe',
            'dni'   => '40678901',
        ]);

        User::factory()->alumno()->count(10)->create();

        User::factory()->alumno()->inactive()->create([
            'name'  => 'Alumno Inactivo',
            'email' => 'inactivo@test.com',
        ]);

        // Estructura académica (orden importa por FKs)
        $this->call([
            AcademicPeriodSeeder::class,
            AcademicSettingsSeeder::class,
            ProgramSeeder::class,
            CourseSectionSeeder::class,
            EnrollmentSeeder::class,
            ScheduleSeeder::class,
            MaterialSeeder::class,
        ]);
    }
}
