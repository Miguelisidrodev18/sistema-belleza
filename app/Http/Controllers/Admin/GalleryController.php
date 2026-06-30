<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public const CATEGORIES = [
        'instalaciones' => 'Nuestras instalaciones',
        'practica'      => 'Práctica profesional',
        'graduacion'    => 'Graduación',
        'estilismo'     => 'Taller de estilismo',
        'maquillaje'    => 'Clase de maquillaje',
        'equipamiento'  => 'Equipamiento',
    ];

    public function store(Request $request, string $category): RedirectResponse
    {
        abort_unless(array_key_exists($category, self::CATEGORIES), 404);

        $request->validate([
            'images'   => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:8192'],
        ]);

        $nextOrder = GalleryImage::where('category', $category)->max('sort_order') + 1;

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('site/gallery', 'public');
            GalleryImage::create([
                'category'   => $category,
                'path'       => $path,
                'sort_order' => $nextOrder++,
            ]);
        }

        return back()->with('success', 'Imágenes subidas correctamente.');
    }

    public function destroy(GalleryImage $galleryImage): RedirectResponse
    {
        Storage::disk('public')->delete($galleryImage->path);
        $galleryImage->delete();

        return back()->with('success', 'Imagen eliminada.');
    }
}
