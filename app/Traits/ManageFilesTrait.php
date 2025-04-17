<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait ManageFilesTrait
{
    public function uploadImageToStorage(array $files, $path = 'public'): array
    {
        $uploadURLs = [];
        foreach ($files as $file) {
            $fileName = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs($path, $file, $fileName);
            $uploadURLs[]= URL::to('/') . '/storage/' . $filePath;
        }
        return $uploadURLs;
    }

    public function deleteImageFromStorage(array $files, $folder= '') : void {
        foreach ($files as $file) {
            $path = parse_url($file, PHP_URL_PATH);
            $storagePath = ltrim($path, "/storage/${folder}");
            if($folder!=''){
                $storagePath = "/$folder/$storagePath";
            }
            $storage = Storage::disk('public');
            $storage->delete($storagePath);
        }
    }
}
