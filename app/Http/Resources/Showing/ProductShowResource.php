<?php

namespace App\Http\Resources\Showing;

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
        $this->resource->load('brand');
        $this->resource->load('category');
        $this->resource->load('image');

        return [
            'brand_id' => $this->brand_id,
            'brand' => new BrandShowResource($this->brand),
            'category_id' => $this->category_id,
            'category' => new CategoryShowResource($this->category),
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->image),
            'is_available' => $this->is_available,
        ];
    }
}
