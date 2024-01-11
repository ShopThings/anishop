<?php

namespace App\Http\Resources\Showing;

use App\Enums\Menus\MenuPlacesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuPlaceShowResource extends JsonResource
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
            'place_in' => $this->place_in,
        ];
    }
}
