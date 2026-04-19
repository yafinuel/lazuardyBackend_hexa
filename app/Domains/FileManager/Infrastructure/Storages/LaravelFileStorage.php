<?php

namespace App\Domains\FileManager\Infrastructure\Storages;

use App\Domains\FileManager\Ports\FileStorageInterface;
use Illuminate\Support\Facades\Storage;

class LaravelFileStorage implements FileStorageInterface
{
    public function getMedia(?string $path): ?string
    {
        if (!$path) {
            return null; 
        }

        $media = Storage::url($path);
        return asset($media) ? $media : null;
    }

    public function uploadToTemp($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $extension;

        return $file->storeAs('temp', $fileName, 'local');
    }

    public function moveToPermanent(string $tempPath, string $folder): string
    {
        $content = Storage::disk('local')->get($tempPath);
        $fileName = basename($tempPath);
        $finalPath = $folder . '/' . $fileName;

        Storage::disk('public')->put($finalPath, $content);

        return $finalPath;
    }

    public function delete(string $path, string $disk): bool
    {
        return Storage::disk($disk)->delete($path);
    }
}