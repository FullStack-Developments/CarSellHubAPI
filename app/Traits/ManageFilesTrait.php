<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

trait ManageFilesTrait
{
    public function uploadFile($file, $path = 'public'): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalName . '_' . time() . '.' . $file->extension();

        $filePath = Storage::disk('public')->putFileAs($path, $file, $fileName);
        return URL::to('/') . '/storage/' . $filePath;
    }
}
