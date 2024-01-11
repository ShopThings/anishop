<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderShowResource extends JsonResource
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
            'slider_place' => new SliderPlaceShowResource($this->whenLoaded('place')),
            'items' => SliderItemShowResource::collection($this->whenLoaded('items')),
            'title' => $this->title,
            'priority' => $this->priority,
            'options' => $this->options,
        ];
    }
}
