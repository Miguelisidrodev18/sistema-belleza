<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Lms\MaterialAttachment;
use App\Models\CourseSection;
use App\Services\Lms\MaterialService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct(private MaterialService $service) {}

    public function download(MaterialAttachment $materialAttachment, Request $request)
    {
        if ($materialAttachment->isLink()) {
            abort(400, 'Los enlaces no se pueden descargar.');
        }

        return $this->service->download($materialAttachment, auth()->user(), $request);
    }

    public function sectionMaterials(CourseSection $courseSection)
    {
        $user = auth()->user();

        $hasAccess = \App\Models\Enrollment::where('alumno_id', $user->id)
            ->where('course_section_id', $courseSection->id)
            ->where('status', 'activa')
            ->exists();

        if (! $hasAccess) {
            abort(403);
        }

        $materials = $this->service->getForSection($courseSection);

        return view('alumno.sections.materials', compact('courseSection', 'materials'));
    }
}
