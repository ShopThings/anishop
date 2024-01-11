<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandShowResource extends JsonResource
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
            'name' => $this->name,
            'latin_name' => $this->latin_name,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->whenLoaded('image')),
        ];
    }
}
