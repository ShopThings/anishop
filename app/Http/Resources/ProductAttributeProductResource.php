<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
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
            'product_attribute_value_id' => $this->product_attribute_value_id,
            'product_attribute_value' => $this->whenLoaded('attrValues'),
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('products', function ($query) {
                $query->where('product_id', $this->product_id);
            }),
            'created_by' => $this->when($this->created_by, $this->creator()),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
