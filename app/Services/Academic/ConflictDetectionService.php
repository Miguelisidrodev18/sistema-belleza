<?php

namespace App\Services\Academic;

use App\Academic\ClassSession;
use App\Models\CourseSection;
use Illuminate\Support\Carbon;

class ConflictDetectionService
{
    public function detectDocente(CourseSection $section, Carbon $startsAt, Carbon $endsAt): ?string
    {
        $teacher = $section->teachers()->wherePivot('is_primary', true)->first();
        if (! $teacher) {
            return null;
        }

        $conflict = ClassSession::whereHas('courseSection.teachers', function ($q) use ($teacher) {
                $q->where('users.id', $teacher->id)
                  ->where('course_section_teachers.is_primary', true);
            })
            ->where('course_section_id', '!=', $section->id)
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->whereNotIn('status', ['cancelled'])
            ->with('courseSection.course')
            ->first();

        if ($conflict) {
            $courseName = $conflict->courseSection->course->name ?? 'otra sección';
            return "El docente ya tiene sesión en \"{$courseName}\" ({$conflict->starts_at->format('H:i')}-{$conflict->ends_at->format('H:i')})";
        }

        return null;
    }

    public function detectRoom(string $room, Carbon $startsAt, Carbon $endsAt, ?int $excludeId = null): ?string
    {
        $query = ClassSession::where('room', $room)
            ->where('starts_at', '<', $endsAt)
            ->where('ends_at', '>', $startsAt)
            ->whereNotIn('status', ['cancelled']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflict = $query->with('courseSection.course')->first();

        if ($conflict) {
            $courseName = $conflict->courseSection->course->name ?? 'otra sesión';
            return "Aula \"{$room}\" ocupada por \"{$courseName}\" ({$conflict->starts_at->format('H:i')}-{$conflict->ends_at->format('H:i')})";
        }

        return null;
    }

    public function detectAll(CourseSection $section, Carbon $startsAt, Carbon $endsAt, ?int $excludeId = null): array
    {
        $conflicts = ['docente' => null, 'room' => null];

        $conflicts['docente'] = $this->detectDocente($section, $startsAt, $endsAt);

        $room = $section->room ?? null;
        if ($room) {
            $conflicts['room'] = $this->detectRoom($room, $startsAt, $endsAt, $excludeId);
        }

        return $conflicts;
    }
}
