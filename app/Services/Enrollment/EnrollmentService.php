<?php

namespace App\Services\Enrollment;

use App\Enums\EnrollmentStatus;
use App\Exceptions\Enrollment\AlreadyEnrolledException;
use App\Models\Enrollment;
use App\Models\EnrollmentActivity;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function __construct(
        protected EnrollmentCapacityService   $capacity,
        protected EnrollmentValidationService $validator,
    ) {}

    public function enroll(User $alumno, CourseSection $section, array $data = []): Enrollment
    {
        $this->validator->validateAll($alumno, $section);

        return DB::transaction(function () use ($alumno, $section, $data) {
            $locked = CourseSection::lockForUpdate()->findOrFail($section->id);
            $this->capacity->checkCapacity($locked);

            if (Enrollment::where('alumno_id', $alumno->id)
                ->where('course_section_id', $locked->id)
                ->exists()) {
                throw new AlreadyEnrolledException();
            }

            $enrollment = Enrollment::create([
                'enrollment_number'  => $this->generateNumber(),
                'alumno_id'          => $alumno->id,
                'course_section_id'  => $locked->id,
                'academic_period_id' => $locked->academic_period_id,
                'status'             => EnrollmentStatus::Activa->value,
                'enrolled_at'        => now()->toDateString(),
                'remarks'            => $data['remarks'] ?? null,
            ]);

            $this->logActivity($enrollment, auth()->user() ?? $alumno, 'enrolled', null, EnrollmentStatus::Activa);

            return $enrollment;
        });
    }

    public function withdraw(Enrollment $enrollment): void
    {
        DB::transaction(function () use ($enrollment) {
            $old = $enrollment->status;
            $enrollment->update([
                'status'       => EnrollmentStatus::Retirada->value,
                'withdrawn_at' => now()->toDateString(),
            ]);
            $this->logActivity($enrollment, auth()->user(), 'withdrawn', $old, EnrollmentStatus::Retirada);
        });
    }

    public function updateStatus(Enrollment $enrollment, EnrollmentStatus $newStatus): void
    {
        $old = $enrollment->status;

        DB::transaction(function () use ($enrollment, $newStatus, $old) {
            $updates = ['status' => $newStatus->value];

            if ($newStatus === EnrollmentStatus::Retirada && ! $enrollment->withdrawn_at) {
                $updates['withdrawn_at'] = now()->toDateString();
            }
            if ($newStatus === EnrollmentStatus::Completada && ! $enrollment->completed_at) {
                $updates['completed_at'] = now()->toDateString();
            }

            $enrollment->update($updates);
            $this->logActivity($enrollment, auth()->user(), 'status_changed', $old, $newStatus);
        });
    }

    private function generateNumber(): string
    {
        $year = now()->year;
        $prefix = "E{$year}-";
        $last = Enrollment::where('enrollment_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->max('enrollment_number');

        $seq = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad($seq, 6, '0', STR_PAD_LEFT);
    }

    private function logActivity(
        Enrollment $enrollment,
        ?User $actor,
        string $action,
        ?EnrollmentStatus $from,
        ?EnrollmentStatus $to,
        ?string $remarks = null,
    ): void {
        if (! $actor) {
            return;
        }

        EnrollmentActivity::create([
            'enrollment_id' => $enrollment->id,
            'performed_by'  => $actor->id,
            'action'        => $action,
            'from_status'   => $from?->value,
            'to_status'     => $to?->value,
            'remarks'       => $remarks,
            'created_at'    => now(),
        ]);
    }
}
