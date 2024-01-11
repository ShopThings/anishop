<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductAttributeShowResource;
use App\Http\Resources\Showing\UserShowResource;
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
            'product_attribute ' => new ProductAttributeShowResource($this->whenLoaded('productAttr')),
            'attribute_value' => $this->attribute_value,
        ];
    }
}
