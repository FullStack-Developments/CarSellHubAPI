<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait ManageFilesTrait
{
    public function uploadFile($file, $path = 'public'): string
    {
        // $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = Storage::disk('public')->putFileAs($path, $file, $fileName);
        return URL::to('/') . '/storage/' . $filePath;
    }
}
