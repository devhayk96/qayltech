<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait MediaHelper
{
    public function upload($file, $path, $userId = false)
    {
        if ($file) {
            $fileExt = $file->getClientOriginalExtension();
            $fileName = uniqid() . $userId ?? '';
            $fileName = $fileName . '.' . $fileExt;
            Storage::disk('public')->put($path . $fileName, File::get($file));
            $filePath = 'storage/' . $path . $fileName;

            return $file = [
                'fileName' => $fileName,
                'filePath' => $filePath,
                'fileSize' => $this->fileSize($file)
            ];
        }
    }

    public function fileSize($file, $precision = 2)
    {
        $size = $file->getSize();

        if ($size > 0) {
            $size = (int)$size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }

}
