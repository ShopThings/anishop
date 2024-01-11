<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\MenuPlaceShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuShowResource extends JsonResource
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
            'menu_place' => new MenuPlaceShowResource($this->whenLoaded('place')),
            'title' => $this->title,
            'is_published' => $this->is_published,
        ];
    }
}
