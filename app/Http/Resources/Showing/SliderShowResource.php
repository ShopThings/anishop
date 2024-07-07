<?php

namespace App\Http\Resources\Showing;

use App\Enums\Sliders\SliderPlacesEnum;
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
            'place_in' => [
                'text' => SliderPlacesEnum::getTranslations($this->place_in, 'نامشخص'),
                'value' => $this->place_in->value,
            ],
            'items' => SliderItemShowResource::collection($this->whenLoaded('items')),
            'title' => $this->title,
            'priority' => $this->priority,
            'options' => $this->options,
        ];
    }
}
