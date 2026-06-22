<?php

namespace App\Http\Controllers\Docente;

use App\Academic\ClassSession;
use App\Http\Controllers\Controller;
use App\Lms\Material;
use App\Services\Lms\MaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function __construct(private MaterialService $service) {}

    public function store(Request $request, ClassSession $classSession)
    {
        $this->authorizeSession($classSession);

        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,mp4,zip|max:51200',
            'link_url'    => 'nullable|url|max:500',
            'link_title'  => 'nullable|string|max:200',
        ]);

        $material = $this->service->store([
            'class_session_id' => $classSession->id,
            'title'            => $data['title'],
            'description'      => $data['description'] ?? null,
            'visibility'       => 'session',
            'is_published'     => true,
        ], auth()->user());

        $version = $material->currentVersion;

        if ($request->hasFile('file')) {
            $this->service->attachFile($version, $request->file('file'), auth()->user());
        } elseif (! empty($data['link_url'])) {
            $this->service->attachLink($version, $data['link_url'], $data['link_title'] ?? '');
        }

        return redirect()->route('docente.class-sessions.show', $classSession)
            ->with('success', 'Material subido correctamente.');
    }

    public function destroy(ClassSession $classSession, Material $material)
    {
        $this->authorizeSession($classSession);

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

        return redirect()->route('docente.class-sessions.show', $classSession)
            ->with('success', 'Material eliminado.');
    }

    private function authorizeSession(ClassSession $session): void
    {
        $teacherId = auth()->id();

        $isMine = $session->courseSection()
            ->whereHas('teachers', fn ($q) => $q
                ->where('users.id', $teacherId)
                ->where('course_section_teachers.is_primary', true))
            ->exists();

        if (! $isMine) {
            abort(403);
        }
    }
}
