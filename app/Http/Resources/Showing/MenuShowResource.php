<?php

namespace App\Http\Resources\Showing;

use App\Enums\Menus\MenuPlacesEnum;
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
            'place_in' => [
                'text' => MenuPlacesEnum::getTranslations($this->place_in, 'نامشخص'),
                'value' => $this->place_in->value,
            ],
            'title' => $this->title,
            'is_published' => $this->is_published,
        ];
    }
}
