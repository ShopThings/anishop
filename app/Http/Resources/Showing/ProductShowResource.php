<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
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
            'brand_id' => $this->brand_id,
            'brand' => new BrandShowResource($this->whenLoaded('brand')),
            'category_id' => $this->category_id,
            'category' => new CategoryShowResource($this->whenLoaded('category')),
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->whenLoaded('image')),
            'is_available' => $this->is_available,
        ];
    }
}
