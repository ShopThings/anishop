<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmazingOfferSliderResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->whenLoaded('image'),
            'unit_name' => $this->unit_name,
            'keywords' => $this->keywords,
            'items' => $this->whenLoaded('items'),
            'festivals' => $this->whenLoaded('festivals'),
            'is_available' => $this->is_available,
            'is_commenting_allowed' => $this->is_commenting_allowed,
        ];
    }
}
