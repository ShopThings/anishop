<?php

namespace App\Http\Resources\Home;

use App\Enums\Sliders\SliderPlacesEnum;
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
        $self = $this;

        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'place_in' => [
                'text' => SliderPlacesEnum::getTranslations($this['place'], 'نامشخص'),
                'value' => $this['place'],
            ],
            'items' => $this->when($this['items'], function () use ($self) {
                if ($self['place']?->value === SliderPlacesEnum::MAIN_SLIDERS->value) {
                    return ProductResource::collection($self['items']);
                }
                return $self['items'];
            }),
            'options' => $this['options'],
        ];
    }
}
