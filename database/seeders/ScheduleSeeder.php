<?php

namespace Database\Seeders;

use App\Academic\Attendance;
use App\Academic\ClassSession;
use App\Academic\Meeting;
use App\Academic\Schedule;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $currentPeriod = AcademicPeriod::where('is_current', true)->first();
        if (! $currentPeriod) {
            return;
        }

        $admin = User::where('role', 'administrador')->first();

        $sections = CourseSection::with(['enrollments.alumno'])
            ->where('academic_period_id', $currentPeriod->id)
            ->where('is_active', true)
            ->get();

        // Day pairs by section index: alternate Tue/Thu and Mon/Wed
        $dayPairs = [
            [2, 4], // Martes + Jueves
            [1, 3], // Lunes + Miércoles
        ];

        // Time slots: sections alternate between morning and evening
        $timeSlots = [
            ['start' => '08:00', 'end' => '11:00'],
            ['start' => '19:00', 'end' => '22:00'],
        ];

        $periodStart = $currentPeriod->start_date
            ? Carbon::parse($currentPeriod->start_date)
            : Carbon::parse('2026-03-02');
        $periodEnd = $currentPeriod->end_date
            ? Carbon::parse($currentPeriod->end_date)
            : Carbon::parse('2026-07-15');

        foreach ($sections as $idx => $section) {
            $pair     = $dayPairs[$idx % 2];
            $timeslot = $timeSlots[$idx % 2];

            // Create 2 schedules (one per weekday)
            $schedules = [];
            foreach ($pair as $dow) {
                $schedules[] = Schedule::create([
                    'course_section_id' => $section->id,
                    'day_of_week'       => $dow,
                    'start_time'        => $timeslot['start'],
                    'end_time'          => $timeslot['end'],
                    'room'              => 'Aula ' . (($idx % 5) + 1),
                    'modality'          => 'presencial',
                    'is_active'         => true,
                ]);
            }

            // Generate ClassSessions for the period
            $sessionNumber = 1;
            $cursor = $periodStart->copy();
            $sessionsToCreate = [];

            while ($cursor->lte($periodEnd)) {
                foreach ($schedules as $schedule) {
                    if ($cursor->isoWeekday() === $schedule->day_of_week) {
                        [$sh, $sm] = explode(':', $schedule->start_time);
                        [$eh, $em] = explode(':', $schedule->end_time);
                        $startsAt = $cursor->copy()->setTime((int) $sh, (int) $sm);
                        $endsAt   = $cursor->copy()->setTime((int) $eh, (int) $em);
                        $sessionsToCreate[] = [
                            'schedule'     => $schedule,
                            'session_number' => $sessionNumber++,
                            'starts_at'    => $startsAt,
                            'ends_at'      => $endsAt,
                        ];
                    }
                }
                $cursor->addDay();
            }

            // Sort by starts_at and insert
            usort($sessionsToCreate, fn($a, $b) => $a['starts_at']->lt($b['starts_at']) ? -1 : 1);
            $sessionNumber = 1;
            foreach ($sessionsToCreate as &$item) {
                $item['session_number'] = $sessionNumber++;
            }
            unset($item);

            $activeEnrollments = $section->enrollments()->where('status', 'activa')->with('alumno')->get();

            foreach ($sessionsToCreate as $item) {
                $isPast = $item['starts_at']->lt(now());

                $session = ClassSession::create([
                    'course_section_id' => $section->id,
                    'schedule_id'       => $item['schedule']->id,
                    'session_number'    => $item['session_number'],
                    'title'             => "Clase #{$item['session_number']}",
                    'starts_at'         => $item['starts_at'],
                    'ends_at'           => $item['ends_at'],
                    'room'              => $item['schedule']->room,
                    'status'            => $isPast ? 'completed' : 'scheduled',
                    'is_generated'      => true,
                ]);

                if ($isPast) {
                    // Create Meeting for past sessions
                    Meeting::create([
                        'class_session_id'   => $session->id,
                        'platform'           => 'zoom',
                        'meeting_url'        => "https://zoom.us/j/fake{$session->id}",
                        'meeting_id'         => "FAKE{$session->id}",
                        'status'             => 'ended',
                        'started_at'         => $item['starts_at'],
                        'ended_at'           => $item['ends_at'],
                        'recording_duration' => 180 * 60, // 3 horas en segundos
                    ]);

                    // Create Attendance for enrolled students
                    foreach ($activeEnrollments as $enrollment) {
                        $rand = rand(1, 10);
                        if ($rand <= 8) {
                            $status       = 'present';
                            $arrivalTime  = null;
                            $departTime   = null;
                        } elseif ($rand === 9) {
                            $status       = 'late';
                            $arrivalTime  = $item['schedule']->start_time; // 15 min late
                            $arrivalCarbon = Carbon::parse($item['starts_at']->format('Y-m-d') . ' ' . $item['schedule']->start_time)->addMinutes(15);
                            $arrivalTime  = $arrivalCarbon->format('H:i');
                            $departTime   = null;
                        } else {
                            $status       = 'absent';
                            $arrivalTime  = null;
                            $departTime   = null;
                        }

                        Attendance::create([
                            'class_session_id' => $session->id,
                            'enrollment_id'    => $enrollment->id,
                            'status'           => $status,
                            'arrival_time'     => $arrivalTime,
                            'departure_time'   => $departTime,
                            'recorded_by'      => $admin->id,
                            'recorded_at'      => $item['ends_at'],
                        ]);
                    }
                }
            }
        }

        // Create ONE session TODAY + 1h from now for the "Próxima clase" banner
        $firstSection = $sections->first();
        if ($firstSection) {
            $startsAt = now()->addHour();
            $endsAt   = now()->addHours(4);

            $todaySession = ClassSession::create([
                'course_section_id' => $firstSection->id,
                'schedule_id'       => null,
                'session_number'    => ClassSession::where('course_section_id', $firstSection->id)->max('session_number') + 1,
                'title'             => 'Clase de Demostración (HOY)',
                'starts_at'         => $startsAt,
                'ends_at'           => $endsAt,
                'status'            => 'scheduled',
                'is_generated'      => false,
            ]);

            Meeting::create([
                'class_session_id' => $todaySession->id,
                'platform'         => 'zoom',
                'meeting_url'      => 'https://zoom.us/j/demo123',
                'meeting_id'       => 'DEMO123',
                'passcode'         => 'ugarte2026',
                'status'           => 'pending',
            ]);
        }
    }
}
