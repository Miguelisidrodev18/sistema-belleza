<?php

namespace App\Services\Academic;

use App\Academic\Attendance;
use App\Academic\ClassSession;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * data = [enrollment_id => ['status' => ..., 'arrival_time' => 'H:i', 'departure_time' => 'H:i', 'notes' => ...]]
     */
    public function bulkRecord(ClassSession $session, array $data, User $actor): void
    {
        DB::transaction(function () use ($session, $data, $actor) {
            foreach ($data as $enrollmentId => $fields) {
                Attendance::updateOrCreate(
                    [
                        'class_session_id' => $session->id,
                        'enrollment_id'    => (int) $enrollmentId,
                    ],
                    [
                        'status'         => $fields['status'] ?? 'absent',
                        'arrival_time'   => $fields['arrival_time'] ?? null,
                        'departure_time' => $fields['departure_time'] ?? null,
                        'notes'          => $fields['notes'] ?? null,
                        'recorded_by'    => $actor->id,
                        'recorded_at'    => now(),
                    ]
                );
            }
        });
    }

    public function getSessionStats(ClassSession $session): array
    {
        $counts = Attendance::where('class_session_id', $session->id)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $total   = array_sum($counts);
        $present = ($counts['present'] ?? 0) + ($counts['late'] ?? 0);

        return [
            'present' => $counts['present'] ?? 0,
            'late'    => $counts['late']    ?? 0,
            'absent'  => $counts['absent']  ?? 0,
            'excused' => $counts['excused'] ?? 0,
            'total'   => $total,
            'rate'    => $total > 0 ? round(($present / $total) * 100) : 0,
        ];
    }

    public function getEnrollmentStats(Enrollment $enrollment): array
    {
        $counts = Attendance::where('enrollment_id', $enrollment->id)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $total   = array_sum($counts);
        $present = ($counts['present'] ?? 0) + ($counts['late'] ?? 0);

        return [
            'present' => $counts['present'] ?? 0,
            'late'    => $counts['late']    ?? 0,
            'absent'  => $counts['absent']  ?? 0,
            'excused' => $counts['excused'] ?? 0,
            'total'   => $total,
            'rate'    => $total > 0 ? round(($present / $total) * 100) : 0,
        ];
    }
}
