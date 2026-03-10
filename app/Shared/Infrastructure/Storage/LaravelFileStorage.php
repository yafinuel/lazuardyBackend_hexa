<?php

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Ports\FileStorageInterface;
use Illuminate\Support\Facades\Storage;

class LaravelFileStorage implements FileStorageInterface
{
    public function uploadToTemp ($file): string
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

    public function delete(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($path);
    }
}
