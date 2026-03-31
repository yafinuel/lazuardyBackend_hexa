<?php

namespace App\Domains\Package\Entities;

class PackageEntity
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $id,
        public string $name,
        public int $session,
        public float $price,
        public float $discount,
        public string $description,
        public ?string $imagePath,
    ) {}

    public function getFinalPrice(): float
    {
        return $this->price - ($this->price * $this->discount);
    }
}
