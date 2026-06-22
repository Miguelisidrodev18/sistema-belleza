<?php

namespace App\Services\Academic;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CourseSectionService
{
    public function create(array $data): CourseSection
    {
        return CourseSection::create($data);
    }

    public function update(CourseSection $section, array $data): CourseSection
    {
        $section->update($data);
        return $section->fresh();
    }

    public function assignTeacher(
        CourseSection $section,
        int $teacherId,
        string $role = 'principal',
        bool $isPrimary = true,
    ): void {
        if ($isPrimary) {
            $section->teachers()->updateExistingPivot(
                $section->teachers()->wherePivot('is_primary', true)->pluck('users.id')->toArray(),
                ['is_primary' => false],
            );
        }

        $section->teachers()->syncWithoutDetaching([
            $teacherId => ['role' => $role, 'is_primary' => $isPrimary],
        ]);
    }

    public function removeTeacher(CourseSection $section, int $teacherId): void
    {
        $section->teachers()->detach($teacherId);
    }

    public function getAvailableTeachers(): Collection
    {
        return User::docentes()->active()->orderBy('name')->get();
    }
}
