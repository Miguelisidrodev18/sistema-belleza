<?php

namespace Database\Seeders;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\EnrollmentActivity;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $period2025 = AcademicPeriod::where('name', 'like', '%2025%')->first();
        $period2026 = AcademicPeriod::where('is_current', true)->first();
        $alumnos    = User::alumnos()->active()->get();
        $admin      = User::admins()->first();

        if (! $period2026 || $alumnos->isEmpty() || ! $admin) {
            return;
        }

        // ── Secciones en 2025-II para re-matrícula ──────────────────────────
        if ($period2025) {
            $this->seed2025Sections($period2025, $alumnos->take(3), $admin);
        }

        // ── Matrículas en 2026-I ─────────────────────────────────────────────
        $sections2026 = CourseSection::where('academic_period_id', $period2026->id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->get();

        $seq = 1;
        foreach ($alumnos->take(5) as $i => $alumno) {
            $section = $sections2026->get($i % $sections2026->count());
            if (! $section) {
                continue;
            }

            if (Enrollment::where('alumno_id', $alumno->id)
                ->where('course_section_id', $section->id)->exists()) {
                continue;
            }

            $number     = 'E2026-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
            $enrollment = Enrollment::create([
                'enrollment_number'  => $number,
                'alumno_id'          => $alumno->id,
                'course_section_id'  => $section->id,
                'academic_period_id' => $period2026->id,
                'status'             => EnrollmentStatus::Activa->value,
                'enrolled_at'        => now()->subDays(rand(1, 10))->toDateString(),
            ]);

            EnrollmentActivity::create([
                'enrollment_id' => $enrollment->id,
                'performed_by'  => $admin->id,
                'action'        => 'enrolled',
                'from_status'   => null,
                'to_status'     => EnrollmentStatus::Activa->value,
                'created_at'    => now(),
            ]);

            $seq++;
        }
    }

    private function seed2025Sections(AcademicPeriod $period2025, $alumnos, User $admin): void
    {
        $docentes = User::docentes()->active()->get();
        if ($docentes->isEmpty()) {
            return;
        }

        // Tomar los primeros 3 cursos (Barbería + Estilismo)
        $courses = Course::whereHas('program', fn($q) => $q->whereIn('slug', ['barberia', 'estilismo']))
            ->take(3)
            ->get();

        if ($courses->isEmpty()) {
            $courses = Course::take(3)->get();
        }

        $seq2025 = 1;
        foreach ($courses as $i => $course) {
            $section = CourseSection::firstOrCreate(
                [
                    'course_id'          => $course->id,
                    'academic_period_id' => $period2025->id,
                    'section_code'       => 'A',
                ],
                [
                    'capacity'  => 30,
                    'status'    => 'finished',
                    'is_active' => false,
                ]
            );

            if ($section->teachers()->count() === 0) {
                $teacher = $docentes[$i % $docentes->count()];
                $section->teachers()->attach($teacher->id, ['role' => 'principal', 'is_primary' => true]);
            }

            $alumno = $alumnos->get($i);
            if (! $alumno) {
                continue;
            }

            if (Enrollment::where('alumno_id', $alumno->id)
                ->where('course_section_id', $section->id)->exists()) {
                continue;
            }

            $number     = 'E2025-' . str_pad($seq2025, 6, '0', STR_PAD_LEFT);
            $enrollment = Enrollment::create([
                'enrollment_number'  => $number,
                'alumno_id'          => $alumno->id,
                'course_section_id'  => $section->id,
                'academic_period_id' => $period2025->id,
                'status'             => EnrollmentStatus::Completada->value,
                'enrolled_at'        => now()->subMonths(6)->toDateString(),
                'completed_at'       => now()->subMonths(1)->toDateString(),
            ]);

            EnrollmentActivity::create([
                'enrollment_id' => $enrollment->id,
                'performed_by'  => $admin->id,
                'action'        => 'enrolled',
                'from_status'   => null,
                'to_status'     => EnrollmentStatus::Activa->value,
                'created_at'    => now()->subMonths(6),
            ]);

            EnrollmentActivity::create([
                'enrollment_id' => $enrollment->id,
                'performed_by'  => $admin->id,
                'action'        => 'status_changed',
                'from_status'   => EnrollmentStatus::Activa->value,
                'to_status'     => EnrollmentStatus::Completada->value,
                'created_at'    => now()->subMonths(1),
            ]);

            $seq2025++;
        }
    }
}
