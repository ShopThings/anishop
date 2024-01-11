<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductAttributeValueShowResource;
use App\Http\Resources\Showing\ProductShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeProductResource extends JsonResource
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
            'product_attribute_value' => new ProductAttributeValueShowResource($this->whenLoaded('attrValue')),
            'product' => new ProductShowResource($this->whenLoaded('product')),
            'created_by' => new UserShowResource($this->when($this->created_by, $this->creator())),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
