<?php

namespace App\Http\Resources\Showing;

use App\Enums\Sliders\SliderPlacesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderPlaceShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'place_in' => $this->place_in,
        ];
    }
}
