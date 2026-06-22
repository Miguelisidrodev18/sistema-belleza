<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Program;
use App\Services\Academic\CourseService;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $service,
    ) {}

    public function create(Program $program)
    {
        return view('admin.programs.courses.create', compact('program'));
    }

    public function store(StoreCourseRequest $request, Program $program)
    {
        $this->service->create($program, $request->validated());

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Curso creado correctamente.');
    }

    public function edit(Program $program, Course $course)
    {
        return view('admin.programs.courses.edit', compact('program', 'course'));
    }

    public function update(UpdateCourseRequest $request, Program $program, Course $course)
    {
        $this->service->update($course, $request->validated());

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Program $program, Course $course)
    {
        $course->delete();

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Curso eliminado.');
    }
}
