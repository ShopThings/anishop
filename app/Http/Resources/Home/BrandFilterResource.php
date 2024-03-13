<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product->brand->id,
            'name' => $this->product->brand->name,
            'latin_name' => $this->product->brand->latin_name,
            'slug' => $this->product->brand->slug,
        ];
    }
}
