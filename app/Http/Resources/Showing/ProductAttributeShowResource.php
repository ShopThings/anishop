<?php

namespace App\Http\Resources\Showing;

use App\Enums\Products\ProductAttributeTypesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeShowResource extends JsonResource
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
            'type' => [
                'text' => ProductAttributeTypesEnum::getTranslations($this->type, 'نامشخص'),
                'value' => $this->type,
            ],
        ];
    }
}
