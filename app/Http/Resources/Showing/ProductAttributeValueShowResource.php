<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeValueShowResource extends JsonResource
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
            'product_attribute_id' => $this->product_attribute_id,
            'attribute_value' => $this->attribute_value,
        ];
    }
}
