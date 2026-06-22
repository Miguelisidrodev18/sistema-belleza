<?php

namespace App\Services\Academic;

use App\Academic\ClassSession;
use App\Academic\Schedule;
use App\Models\CourseSection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClassSessionGeneratorService
{
    public function __construct(protected ConflictDetectionService $conflicts) {}

    /**
     * Returns a Collection of DTOs for the preview step.
     * Each item: index, session_number, starts_at, ends_at, schedule, conflicts
     */
    public function preview(
        CourseSection $section,
        Carbon $from,
        Carbon $to,
        array $excludeDates = []
    ): Collection {
        $schedules = $section->schedules()->where('is_active', true)->get();
        if ($schedules->isEmpty()) {
            return collect();
        }

        $excludeSet  = collect($excludeDates)->map(fn($d) => Carbon::parse($d)->toDateString())->flip();
        $nextNumber  = ClassSession::where('course_section_id', $section->id)->max('session_number') ?? 0;
        $candidates  = collect();
        $index       = 0;

        // Walk each day in the range
        $cursor = $from->copy()->startOfDay();
        while ($cursor->lte($to)) {
            $dow = $cursor->isoWeekday(); // 1=Mon, 7=Sun

            foreach ($schedules as $schedule) {
                if ($schedule->day_of_week !== $dow) {
                    continue;
                }
                if ($excludeSet->has($cursor->toDateString())) {
                    continue;
                }

                [$startH, $startM] = explode(':', $schedule->start_time);
                [$endH,   $endM]   = explode(':', $schedule->end_time);
                $startsAt = $cursor->copy()->setTime((int) $startH, (int) $startM);
                $endsAt   = $cursor->copy()->setTime((int) $endH,   (int) $endM);

                $detectedConflicts = $this->conflicts->detectAll($section, $startsAt, $endsAt);

                $candidates->push((object) [
                    'index'          => $index,
                    'session_number' => ++$nextNumber,
                    'starts_at'      => $startsAt->copy(),
                    'ends_at'        => $endsAt->copy(),
                    'schedule'       => $schedule,
                    'conflicts'      => $detectedConflicts,
                    'has_conflict'   => ! empty(array_filter($detectedConflicts)),
                ]);
                $index++;
            }
            $cursor->addDay();
        }

        return $candidates;
    }

    /**
     * Generates ClassSessions in a DB transaction.
     * overrides = [index => ['room', 'starts_at', 'ends_at', 'ignore_conflict' => true]]
     * excluded_indices = [index, ...]
     */
    public function generate(
        CourseSection $section,
        Carbon $from,
        Carbon $to,
        array $excludeDates = [],
        array $overrides = [],
        array $excludedIndices = []
    ): array {
        $candidates = $this->preview($section, $from, $to, $excludeDates);
        $results    = ['created' => 0, 'skipped' => 0, 'conflicts_ignored' => 0];

        DB::transaction(function () use ($section, $candidates, $overrides, $excludedIndices, &$results) {
            // Re-fetch max session_number inside transaction
            $nextNumber = ClassSession::where('course_section_id', $section->id)->max('session_number') ?? 0;

            foreach ($candidates as $item) {
                if (in_array($item->index, $excludedIndices)) {
                    $results['skipped']++;
                    continue;
                }

                $override  = $overrides[$item->index] ?? [];
                $startsAt  = isset($override['starts_at']) ? Carbon::parse($override['starts_at']) : $item->starts_at;
                $endsAt    = isset($override['ends_at'])   ? Carbon::parse($override['ends_at'])   : $item->ends_at;
                $room      = $override['room'] ?? null;
                $ignored   = ! empty($override['ignore_conflict']);

                if ($item->has_conflict && ! $ignored && empty($override)) {
                    $results['conflicts_ignored']++;
                }

                ClassSession::create([
                    'course_section_id' => $section->id,
                    'schedule_id'       => $item->schedule->id,
                    'session_number'    => ++$nextNumber,
                    'starts_at'         => $startsAt,
                    'ends_at'           => $endsAt,
                    'room'              => $room,
                    'modality'          => null, // inherits from schedule
                    'status'            => 'scheduled',
                    'is_generated'      => true,
                ]);
                $results['created']++;

                if ($item->has_conflict && $ignored) {
                    $results['conflicts_ignored']++;
                }
            }
        });

        return $results;
    }
}
