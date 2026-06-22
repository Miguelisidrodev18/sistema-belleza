<?php

namespace App\Services\Academic;

use App\Models\Course;
use App\Models\Program;
use Illuminate\Support\Str;

class CourseService
{
    public function create(Program $program, array $data): Course
    {
        $data['program_id'] = $program->id;
        $data['slug'] = $this->generateSlug($program, $data['name']);
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        if (isset($data['name']) && $data['name'] !== $course->name) {
            $data['slug'] = $this->generateSlug($course->program, $data['name'], $course->id);
        }
        $course->update($data);
        return $course->fresh();
    }

    public function generateSlug(Program $program, string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            Course::withTrashed()
                ->where('program_id', $program->id)
                ->where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
