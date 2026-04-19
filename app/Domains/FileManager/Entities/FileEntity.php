<?php

namespace App\Domains\FileManager\Entities;

class FileEntity {
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $userId,
        public readonly ?string $name,
        public readonly ?string $type,
        public ?string $url,
        public ?string $status,
    ) {}
}
