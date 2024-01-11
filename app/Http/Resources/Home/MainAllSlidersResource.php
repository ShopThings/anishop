<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\ProductShowResource;
use App\Http\Resources\Showing\SliderPlaceShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainAllSlidersResource extends JsonResource
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
            'place' => new SliderPlaceShowResource($this->place),
            'items' => ProductShowResource::collection($this->items),
            'options' => $this->options,
        ];
    }
}
