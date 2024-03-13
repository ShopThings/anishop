<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\ImageShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('image');

        return [
            'image' => new ImageShowResource($this->image),
            'link' => $this->link,
        ];
    }
}
