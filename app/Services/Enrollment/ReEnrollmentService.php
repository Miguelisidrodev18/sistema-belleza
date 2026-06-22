<?php

namespace App\Services\Enrollment;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\EnrollmentActivity;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReEnrollmentService
{
    public function __construct(protected EnrollmentService $enrollmentService) {}

    /**
     * Returns a collection of DTOs for the preview table.
     * Each item: enrollment, alumno, program, course, sourceSection, targetSection|null
     */
    public function getEligibleStudents(AcademicPeriod $source, AcademicPeriod $target): Collection
    {
        $sourceEnrollments = Enrollment::with([
                'alumno',
                'courseSection.course.program',
                'courseSection.academicPeriod',
            ])
            ->where('academic_period_id', $source->id)
            ->whereIn('status', [EnrollmentStatus::Activa->value, EnrollmentStatus::Completada->value])
            ->whereNotIn('alumno_id', function ($q) use ($target) {
                $q->select('alumno_id')
                    ->from('enrollments')
                    ->where('academic_period_id', $target->id);
            })
            ->get();

        return $sourceEnrollments->map(function (Enrollment $enrollment) use ($target) {
            $sourceSection = $enrollment->courseSection;
            $targetSection = CourseSection::where('course_id', $sourceSection->course_id)
                ->where('section_code', $sourceSection->section_code)
                ->where('academic_period_id', $target->id)
                ->first();

            return (object) [
                'enrollment'    => $enrollment,
                'alumno'        => $enrollment->alumno,
                'program'       => $sourceSection->course->program,
                'course'        => $sourceSection->course,
                'sourceSection' => $sourceSection,
                'targetSection' => $targetSection,
                'canEnroll'     => $targetSection !== null,
                'observation'   => $targetSection ? null : 'Sin sección equivalente en el período destino',
            ];
        });
    }

    /**
     * Creates enrollments for the selected source enrollments IDs into the target period.
     * Always sets status = 'activa', regardless of source status.
     * Idempotent: if already enrolled in target → counted as skipped.
     */
    public function executeReEnrollment(array $enrollmentIds, AcademicPeriod $target, User $actor): array
    {
        $results = ['enrolled' => 0, 'skipped' => 0, 'errors' => []];

        $sourceEnrollments = Enrollment::with(['courseSection'])
            ->whereIn('id', $enrollmentIds)
            ->get();

        DB::transaction(function () use ($sourceEnrollments, $target, $actor, &$results) {
            foreach ($sourceEnrollments as $sourceEnrollment) {
                $sourceSection = $sourceEnrollment->courseSection;

                $targetSection = CourseSection::where('course_id', $sourceSection->course_id)
                    ->where('section_code', $sourceSection->section_code)
                    ->where('academic_period_id', $target->id)
                    ->first();

                if (! $targetSection) {
                    $results['skipped']++;
                    $courseName = $sourceSection->course->name ?? 'curso';
                    $results['errors'][] = "Sin sección equivalente para {$courseName}.";
                    continue;
                }

                $alreadyExists = Enrollment::where('alumno_id', $sourceEnrollment->alumno_id)
                    ->where('course_section_id', $targetSection->id)
                    ->exists();

                if ($alreadyExists) {
                    $results['skipped']++;
                    continue;
                }

                $locked = CourseSection::lockForUpdate()->findOrFail($targetSection->id);
                $enrolled = $locked->enrollments()->where('status', 'activa')->count();

                if ($enrolled >= $locked->capacity) {
                    $results['skipped']++;
                    $courseNameFull = $sourceSection->course->name ?? 'curso';
                    $results['errors'][] = "Sección llena para {$courseNameFull}.";
                    continue;
                }

                $year = now()->year;
                $prefix = "E{$year}-";
                $last = Enrollment::where('enrollment_number', 'like', "{$prefix}%")
                    ->lockForUpdate()
                    ->max('enrollment_number');
                $seq = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;
                $number = $prefix . str_pad($seq, 6, '0', STR_PAD_LEFT);

                $newEnrollment = Enrollment::create([
                    'enrollment_number'  => $number,
                    'alumno_id'          => $sourceEnrollment->alumno_id,
                    'course_section_id'  => $locked->id,
                    'academic_period_id' => $target->id,
                    'status'             => EnrollmentStatus::Activa->value,
                    'enrolled_at'        => now()->toDateString(),
                ]);

                EnrollmentActivity::create([
                    'enrollment_id' => $newEnrollment->id,
                    'performed_by'  => $actor->id,
                    'action'        => 're_enrolled',
                    'from_status'   => null,
                    'to_status'     => EnrollmentStatus::Activa->value,
                    'remarks'       => "Re-matrícula desde período ID {$sourceEnrollment->academic_period_id}",
                    'created_at'    => now(),
                ]);

                $results['enrolled']++;
            }
        });

        return $results;
    }
}
