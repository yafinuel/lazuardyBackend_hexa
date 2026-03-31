<?php

namespace App\Domains\Package\Infrastructure\Delivery\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'package_name' => $this->name,
            'total_session' => $this->session . ' Sesi',
            'base_price' => $this->price,
            'discount_rate' => (float) $this->discount, 
            'final_price' => $this->getFinalPrice(),
            'info' => $this->description,
            'image_url' => $this->imagePath ? asset('storage/' . $this->imagePath) : null,
        ];
    }
}
