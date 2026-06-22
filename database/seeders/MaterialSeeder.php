<?php

namespace Database\Seeders;

use App\Lms\Material;
use App\Lms\MaterialAttachment;
use App\Lms\MaterialDownload;
use App\Lms\MaterialVersion;
use App\Models\AcademicPeriod;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $disk  = config('filesystems.default');
        $admin = User::where('role', 'administrador')->first();

        $currentPeriod = AcademicPeriod::where('is_current', true)->first();
        if (! $currentPeriod || ! $admin) {
            return;
        }

        $sections = CourseSection::where('academic_period_id', $currentPeriod->id)
            ->whereIn('status', ['published', 'active'])
            ->with(['course', 'enrollments' => fn ($q) => $q->where('status', 'activa')])
            ->get();

        foreach ($sections as $section) {
            // 2 general materials per section
            $generalTitles = [
                ['Guía de la sección',    'Documento principal del curso con objetivos, cronograma y evaluación.'],
                ['Recursos complementarios', 'Colección de enlaces y materiales de apoyo para el aprendizaje.'],
            ];

            foreach ($generalTitles as [$title, $desc]) {
                $isLink = str_contains($title, 'Recursos');

                $material = Material::create([
                    'course_section_id' => $section->id,
                    'class_session_id'  => null,
                    'title'             => $title,
                    'description'       => $desc,
                    'visibility'        => 'section',
                    'is_published'      => true,
                    'order'             => 0,
                    'created_by'        => $admin->id,
                ]);

                $version = MaterialVersion::create([
                    'material_id'    => $material->id,
                    'version_number' => 1,
                    'notes'          => null,
                    'is_current'     => true,
                    'created_by'     => $admin->id,
                    'created_at'     => now(),
                ]);

                if ($isLink) {
                    MaterialAttachment::create([
                        'material_version_id' => $version->id,
                        'type'                => 'link',
                        'title'               => 'Material de referencia',
                        'original_name'       => null,
                        'disk'                => 'link',
                        'path'                => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'mime_type'           => null,
                        'size_bytes'          => null,
                        'sort'                => 0,
                        'created_at'          => now(),
                    ]);
                } else {
                    // Create a fake PDF file in storage
                    $storagePath = "materials/{$section->id}/{$material->id}/1/guia-seccion.pdf";
                    $fakeContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n%%EOF";

                    Storage::disk($disk)->put($storagePath, $fakeContent);

                    MaterialAttachment::create([
                        'material_version_id' => $version->id,
                        'type'                => 'pdf',
                        'title'               => null,
                        'original_name'       => 'guia-seccion.pdf',
                        'disk'                => $disk,
                        'path'                => $storagePath,
                        'mime_type'           => 'application/pdf',
                        'size_bytes'          => strlen($fakeContent),
                        'sort'                => 0,
                        'created_at'          => now(),
                    ]);
                }
            }

            // Session materials: last 10 completed sessions per section
            $completedSessions = \App\Academic\ClassSession::where('course_section_id', $section->id)
                ->where('status', 'completed')
                ->orderByDesc('starts_at')
                ->limit(10)
                ->get();

            foreach ($completedSessions as $session) {
                $sessionTitle = "Presentación: " . ($session->title ?? "Clase #{$session->session_number}");

                $material = Material::create([
                    'course_section_id' => $section->id,
                    'class_session_id'  => $session->id,
                    'title'             => $sessionTitle,
                    'description'       => 'Material presentado durante la sesión.',
                    'visibility'        => 'session',
                    'is_published'      => true,
                    'order'             => 0,
                    'created_by'        => $admin->id,
                ]);

                $version = MaterialVersion::create([
                    'material_id'    => $material->id,
                    'version_number' => 1,
                    'notes'          => null,
                    'is_current'     => true,
                    'created_by'     => $admin->id,
                    'created_at'     => now(),
                ]);

                $storagePath = "materials/{$section->id}/{$material->id}/1/presentacion-clase-{$session->session_number}.pdf";
                $fakeContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n%%EOF";

                Storage::disk($disk)->put($storagePath, $fakeContent);

                $attachment = MaterialAttachment::create([
                    'material_version_id' => $version->id,
                    'type'                => 'pdf',
                    'title'               => null,
                    'original_name'       => "presentacion-clase-{$session->session_number}.pdf",
                    'disk'                => $disk,
                    'path'                => $storagePath,
                    'mime_type'           => 'application/pdf',
                    'size_bytes'          => strlen($fakeContent),
                    'sort'                => 0,
                    'created_at'          => now(),
                ]);

                // 3 downloads per active alumno
                $alumnoIds = $section->enrollments->pluck('alumno_id');
                foreach ($alumnoIds->take(5) as $alumnoId) {
                    MaterialDownload::create([
                        'material_attachment_id' => $attachment->id,
                        'alumno_id'              => $alumnoId,
                        'downloaded_at'          => now()->subDays(rand(1, 30)),
                        'ip_address'             => '127.0.0.1',
                    ]);
                }
            }
        }
    }
}
