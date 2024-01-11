<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\ProductPropertyResource;
use App\Http\Resources\Showing\FestivalShowResource;
use App\Http\Resources\Showing\ImageShowResource;
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
            'image' => new ImageShowResource($this->whenLoaded('image')),
            'items' => ProductPropertyResource::collection($this->whenLoaded('items')),
            'festivals' => FestivalShowResource::collection($this->whenLoaded('festivals')),
            'is_available' => $this->is_available,
        ];
    }
}
