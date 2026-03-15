<?php

namespace App\Shared\Ports;

interface FileStorageInterface
{
    public function getMedia(?string $path): ?string;
    public function uploadToTemp($file): string;
    public function moveToPermanent(string $tempPath, string $folder): string;
    public function delete(string $path, string $disk): bool;
}
