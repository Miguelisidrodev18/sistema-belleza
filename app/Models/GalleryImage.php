<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    protected $fillable = ['category', 'path', 'caption', 'sort_order'];

    public function url(): string
    {
        return url('storage/' . $this->path);
    }
}
