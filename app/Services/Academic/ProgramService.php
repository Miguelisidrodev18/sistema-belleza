<?php

namespace App\Services\Academic;

use App\Models\Program;
use Illuminate\Support\Str;

class ProgramService
{
    public function create(array $data): Program
    {
        $data['slug'] = $this->generateSlug($data['name']);
        return Program::create($data);
    }

    public function update(Program $program, array $data): Program
    {
        if (isset($data['name']) && $data['name'] !== $program->name) {
            $data['slug'] = $this->generateSlug($data['name'], $program->id);
        }
        $program->update($data);
        return $program->fresh();
    }

    public function generateSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            Program::withTrashed()
                ->where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
