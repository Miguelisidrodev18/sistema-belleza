<?php

namespace App\Services\Enrollment;

use App\Exceptions\Enrollment\AlreadyEnrolledException;
use App\Exceptions\Enrollment\InvalidStudentException;
use App\Exceptions\Enrollment\SectionFullException;
use App\Exceptions\Enrollment\SectionNotAvailableException;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BulkEnrollmentService
{
    public function __construct(protected EnrollmentService $enrollmentService) {}

    public function enrollMany(CourseSection $section, array $alumnoIds, ?string $remarks = null): array
    {
        $results = ['enrolled' => 0, 'skipped' => 0, 'errors' => []];
        $alumnos = User::whereIn('id', $alumnoIds)->get()->keyBy('id');

        DB::transaction(function () use ($section, $alumnos, $alumnoIds, $remarks, &$results) {
            foreach ($alumnoIds as $alumnoId) {
                $alumno = $alumnos->get($alumnoId);

                if (! $alumno) {
                    $results['skipped']++;
                    $results['errors'][] = "ID {$alumnoId}: Alumno no encontrado.";
                    continue;
                }

                try {
                    $this->enrollmentService->enroll($alumno, $section, ['remarks' => $remarks]);
                    $results['enrolled']++;
                } catch (AlreadyEnrolledException $e) {
                    $results['skipped']++;
                    $results['errors'][] = "{$alumno->name}: Ya está matriculado en esta sección.";
                } catch (InvalidStudentException $e) {
                    $results['skipped']++;
                    $results['errors'][] = "{$alumno->name}: {$e->getMessage()}";
                } catch (SectionFullException) {
                    $results['skipped']++;
                    $results['errors'][] = "{$alumno->name}: Sección llena.";

                    $remaining = count($alumnoIds) - $results['enrolled'] - $results['skipped'];
                    if ($remaining > 0) {
                        $results['skipped'] += $remaining;
                        $results['errors'][] = "{$remaining} alumno(s) adicionales omitidos: sección llena.";
                    }
                    break;
                } catch (SectionNotAvailableException $e) {
                    $results['skipped']++;
                    $results['errors'][] = "{$alumno->name}: {$e->getMessage()}";
                    break;
                }
            }
        });

        return $results;
    }
}
