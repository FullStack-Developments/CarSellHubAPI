<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait ManageFilesTrait
{
    public function uploadImage(array $files, $path = 'public'): array
    {
        $uploadURLs = [];
        foreach ($files as $file) {
            $fileName = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs($path, $file, $fileName);
            $uploadURLs[]= URL::to('/') . '/storage/' . $filePath;
        }
        return $uploadURLs;
    }
}
