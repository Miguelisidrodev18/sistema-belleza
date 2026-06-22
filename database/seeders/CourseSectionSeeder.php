<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSectionSeeder extends Seeder
{
    public function run(): void
    {
        $period = AcademicPeriod::where('is_current', true)->first();
        if (! $period) {
            return;
        }

        $docentes = User::docentes()->active()->get();
        if ($docentes->isEmpty()) {
            return;
        }

        $courses = Course::active()->get();
        $docenteIndex = 0;

        foreach ($courses as $course) {
            $section = CourseSection::create([
                'course_id'          => $course->id,
                'academic_period_id' => $period->id,
                'section_code'       => 'A',
                'capacity'           => 30,
                'status'             => 'published',
                'is_active'          => true,
            ]);

            $teacher = $docentes[$docenteIndex % $docentes->count()];
            $section->teachers()->attach($teacher->id, [
                'role'       => 'principal',
                'is_primary' => true,
            ]);

            $docenteIndex++;
        }
    }
}
