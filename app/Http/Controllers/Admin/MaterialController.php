<?php

namespace App\Http\Controllers\Admin;

use App\Academic\ClassSession;
use App\Http\Controllers\Controller;
use App\Lms\Material;
use App\Models\CourseSection;
use App\Services\Lms\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function __construct(private MaterialService $service) {}

    public function store(Request $request, CourseSection $courseSection)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'visibility'  => 'required|in:section,session,private',
            'is_published'=> 'boolean',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4,zip|max:51200',
            'link_url'    => 'nullable|url|max:500',
            'link_title'  => 'nullable|string|max:200',
        ]);

        $material = $this->service->store([
            'course_section_id' => $courseSection->id,
            'title'             => $data['title'],
            'description'       => $data['description'] ?? null,
            'visibility'        => $data['visibility'],
            'is_published'      => $request->boolean('is_published', true),
        ], auth()->user());

        $version = $material->currentVersion;

        if ($request->hasFile('file')) {
            $this->service->attachFile($version, $request->file('file'), auth()->user());
        } elseif (! empty($data['link_url'])) {
            $this->service->attachLink($version, $data['link_url'], $data['link_title'] ?? '');
        }

        return redirect()->route('admin.course-sections.show', [$courseSection, 'tab' => 'materials'])
            ->with('success', 'Material creado correctamente.');
    }

    public function destroy(CourseSection $courseSection, Material $material)
    {
        if ($material->course_section_id !== $courseSection->id) {
            abort(404);
        }

        foreach ($material->versions as $version) {
            foreach ($version->attachments as $attachment) {
                if (! $attachment->isLink()) {
                    Storage::disk($attachment->disk)->delete($attachment->path);
                }
            }
        }

        $material->delete();

        return redirect()->route('admin.course-sections.show', [$courseSection, 'tab' => 'materials'])
            ->with('success', 'Material eliminado.');
    }

    public function storeSession(Request $request, ClassSession $classSession)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'is_published'=> 'boolean',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4,zip|max:51200',
            'link_url'    => 'nullable|url|max:500',
            'link_title'  => 'nullable|string|max:200',
        ]);

        $material = $this->service->store([
            'class_session_id' => $classSession->id,
            'title'            => $data['title'],
            'description'      => $data['description'] ?? null,
            'visibility'       => 'session',
            'is_published'     => $request->boolean('is_published', true),
        ], auth()->user());

        $version = $material->currentVersion;

        if ($request->hasFile('file')) {
            $this->service->attachFile($version, $request->file('file'), auth()->user());
        } elseif (! empty($data['link_url'])) {
            $this->service->attachLink($version, $data['link_url'], $data['link_title'] ?? '');
        }

        return redirect()->route('admin.class-sessions.edit', $classSession)
            ->with('success', 'Material de sesión creado.');
    }

    public function destroySession(ClassSession $classSession, Material $material)
    {
        if ($material->class_session_id !== $classSession->id) {
            abort(404);
        }

        foreach ($material->versions as $version) {
            foreach ($version->attachments as $attachment) {
                if (! $attachment->isLink()) {
                    Storage::disk($attachment->disk)->delete($attachment->path);
                }
            }
        }

        $material->delete();

        return redirect()->route('admin.class-sessions.edit', $classSession)
            ->with('success', 'Material eliminado.');
    }
}
