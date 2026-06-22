<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = require database_path('seeders/data/programs.php');

        foreach ($programs as $programData) {
            $courses = $programData['courses'] ?? [];
            unset($programData['courses']);

            $programData['slug'] = Str::slug($programData['name']);

            $program = Program::create($programData);

            foreach ($courses as $courseData) {
                $courseData['program_id'] = $program->id;
                $courseData['slug'] = Str::slug($courseData['name']);
                $courseData['is_active'] = true;
                Course::create($courseData);
            }
        }
    }
}
