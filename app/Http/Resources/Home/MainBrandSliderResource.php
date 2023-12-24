<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainBrandSliderResource extends JsonResource
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
            'latin_name' => $this->latin_name,
            'slug' => $this->slug,
            'image' => $this->whenLoaded('image', function () {
                return ['path' => $this->image->full_path];
            }),
        ];
    }
}
