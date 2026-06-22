<?php

namespace App\Services\Lms;

use App\Academic\ClassSession;
use App\Enums\MaterialAttachmentType;
use App\Enums\MaterialVisibility;
use App\Lms\Material;
use App\Lms\MaterialAttachment;
use App\Lms\MaterialDownload;
use App\Lms\MaterialVersion;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialService
{
    public function store(array $data, User $actor): Material
    {
        return DB::transaction(function () use ($data, $actor) {
            $material = Material::create([
                'course_section_id' => $data['course_section_id'] ?? null,
                'class_session_id'  => $data['class_session_id'] ?? null,
                'title'             => $data['title'],
                'description'       => $data['description'] ?? null,
                'visibility'        => $data['visibility'] ?? MaterialVisibility::Section->value,
                'is_published'      => $data['is_published'] ?? false,
                'order'             => $data['order'] ?? 0,
                'created_by'        => $actor->id,
            ]);

            MaterialVersion::create([
                'material_id'    => $material->id,
                'version_number' => 1,
                'notes'          => $data['notes'] ?? null,
                'is_current'     => true,
                'created_by'     => $actor->id,
                'created_at'     => now(),
            ]);

            return $material;
        });
    }

    public function addVersion(Material $material, User $actor, string $notes = ''): MaterialVersion
    {
        return DB::transaction(function () use ($material, $actor, $notes) {
            MaterialVersion::where('material_id', $material->id)
                ->lockForUpdate()
                ->update(['is_current' => false]);

            $nextNumber = MaterialVersion::where('material_id', $material->id)->max('version_number') + 1;

            return MaterialVersion::create([
                'material_id'    => $material->id,
                'version_number' => $nextNumber,
                'notes'          => $notes ?: null,
                'is_current'     => true,
                'created_by'     => $actor->id,
                'created_at'     => now(),
            ]);
        });
    }

    public function attachFile(MaterialVersion $version, UploadedFile $file, User $actor): MaterialAttachment
    {
        $disk        = config('filesystems.default');
        $material    = $version->material;
        $contextId   = $material->class_session_id ?? $material->course_section_id;
        $sanitized   = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $file->getClientOriginalExtension();
        $path        = "materials/{$contextId}/{$material->id}/{$version->version_number}/{$sanitized}";

        Storage::disk($disk)->putFileAs(
            "materials/{$contextId}/{$material->id}/{$version->version_number}",
            $file,
            $sanitized
        );

        $type = $this->guessType($file->getClientOriginalExtension(), $file->getMimeType());

        return MaterialAttachment::create([
            'material_version_id' => $version->id,
            'type'                => $type->value,
            'title'               => null,
            'original_name'       => $file->getClientOriginalName(),
            'disk'                => $disk,
            'path'                => $path,
            'mime_type'           => $file->getMimeType(),
            'size_bytes'          => $file->getSize(),
            'sort'                => 0,
            'created_at'          => now(),
        ]);
    }

    public function attachLink(MaterialVersion $version, string $url, string $title): MaterialAttachment
    {
        return MaterialAttachment::create([
            'material_version_id' => $version->id,
            'type'                => MaterialAttachmentType::Link->value,
            'title'               => $title ?: null,
            'original_name'       => null,
            'disk'                => 'link',
            'path'                => $url,
            'mime_type'           => null,
            'size_bytes'          => null,
            'sort'                => 0,
            'created_at'          => now(),
        ]);
    }

    public function download(MaterialAttachment $attachment, User $alumno, Request $request): StreamedResponse
    {
        $material = $attachment->materialVersion->material;

        if (! $this->canAccess($alumno, $material)) {
            abort(403, 'No tienes acceso a este material.');
        }

        MaterialDownload::create([
            'material_attachment_id' => $attachment->id,
            'alumno_id'              => $alumno->id,
            'downloaded_at'          => now(),
            'ip_address'             => $request->ip(),
        ]);

        return Storage::disk($attachment->disk)->download(
            $attachment->path,
            $attachment->original_name ?? basename($attachment->path)
        );
    }

    public function canAccess(User $user, Material $material): bool
    {
        if ($user->role === 'administrador') {
            return true;
        }

        $sectionId = $material->course_section_id
            ?? $material->classSession?->course_section_id;

        if ($sectionId === null) {
            return false;
        }

        if ($user->role === 'docente') {
            return CourseSection::where('id', $sectionId)
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
        }

        // alumno
        return Enrollment::where('alumno_id', $user->id)
            ->where('course_section_id', $sectionId)
            ->where('status', 'activa')
            ->exists();
    }

    public function getForSection(CourseSection $section): Collection
    {
        return Material::with(['currentVersion.attachments'])
            ->forSection($section->id)
            ->published()
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    public function getForSession(ClassSession $session): Collection
    {
        return Material::with(['currentVersion.attachments'])
            ->forSession($session->id)
            ->published()
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
    }

    private function guessType(string $extension, ?string $mime): MaterialAttachmentType
    {
        return match (strtolower($extension)) {
            'pdf'                   => MaterialAttachmentType::Pdf,
            'mp4', 'mov', 'avi'     => MaterialAttachmentType::Video,
            'jpg', 'jpeg', 'png', 'gif', 'webp' => MaterialAttachmentType::Image,
            'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods' => MaterialAttachmentType::Document,
            'zip', 'rar', '7z', 'tar', 'gz' => MaterialAttachmentType::Archive,
            default                 => MaterialAttachmentType::Other,
        };
    }
}
