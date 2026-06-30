<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LandingSettingController extends Controller
{
    private const GALLERY_SLOTS = 6;

    private const MEDIA_KEYS = [
        'hero_image', 'hero_video', 'about_image',
        'gallery_1', 'gallery_2', 'gallery_3', 'gallery_4', 'gallery_5', 'gallery_6',
    ];

    public function edit(): View
    {
        $settings = collect(['hero_bg_type', 'hero_image', 'hero_video', 'hero_video_start', 'hero_video_end', 'about_image'])
            ->mapWithKeys(fn ($key) => [$key => SiteSetting::get($key)]);

        $gallery = GalleryImage::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('admin.site-settings.landing', [
            'settings' => $settings,
            'gallery'  => $gallery,
            'galleryCategories' => GalleryController::CATEGORIES,
        ]);
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_bg_type'       => ['required', 'in:gradient,image,video'],
            'hero_image'         => ['nullable', 'image', 'max:8192'],
            'hero_video'         => ['nullable', 'mimes:mp4,webm,mov', 'max:71680'],
            'hero_video_start'   => ['nullable', 'numeric', 'min:0'],
            'hero_video_end'     => ['nullable', 'numeric', 'min:0'],
        ]);

        SiteSetting::set('hero_bg_type', $validated['hero_bg_type']);

        if ($request->hasFile('hero_image')) {
            $this->replaceFile('hero_image', $request->file('hero_image'));
        }

        if ($request->hasFile('hero_video')) {
            $this->replaceFile('hero_video', $request->file('hero_video'));
            SiteSetting::set('hero_video_start', 0);
            SiteSetting::set('hero_video_end', 0);
        } elseif (isset($validated['hero_video_start'], $validated['hero_video_end'])) {
            SiteSetting::set('hero_video_start', (float) $validated['hero_video_start']);
            SiteSetting::set('hero_video_end',   (float) $validated['hero_video_end']);
        }

        return back()->with('success', 'El fondo del Hero se actualizó correctamente.');
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $request->validate([
            'about_image' => ['required', 'image', 'max:8192'],
        ]);

        $this->replaceFile('about_image', $request->file('about_image'));

        return back()->with('success', 'La imagen de "Quiénes somos" se actualizó correctamente.');
    }

    public function updateGallery(Request $request): RedirectResponse
    {
        $request->validate([
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'image', 'max:8192'],
        ]);

        $updated = 0;

        foreach ($request->file('gallery', []) as $slot => $file) {
            if (! $file) {
                continue;
            }

            $this->replaceFile("gallery_{$slot}", $file);
            $updated++;
        }

        if ($updated === 0) {
            return back()->with('error', 'No seleccionaste ninguna imagen nueva.');
        }

        return back()->with('success', "Se actualizaron {$updated} imagen(es) de la galería.");
    }

    public function removeMedia(string $key): RedirectResponse
    {
        abort_unless(in_array($key, self::MEDIA_KEYS, true), 404);

        $path = SiteSetting::get($key);
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        SiteSetting::set($key, null);

        return back()->with('success', 'Archivo eliminado.');
    }

    private function replaceFile(string $key, UploadedFile $file): void
    {
        $old = SiteSetting::get($key);
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $folder = match (true) {
            str_starts_with($key, 'hero_') => 'site/hero',
            str_starts_with($key, 'gallery_') => 'site/gallery',
            default => 'site/general',
        };

        $path = $file->store($folder, 'public');
        SiteSetting::set($key, $path);
    }
}
