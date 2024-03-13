<?php

namespace App\Http\Resources\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DynamicFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product_attr_values->attr_values->product_attr->id,
            'title' => $this->product_attr_values->attr_values->product_attr->title,
            'type' => $this->product_attr_values->attr_values->product_attr->type,
            'values' => [
                'id' => $this->product_attr_values->attr_values->id,
                'attribute_value' => $this->product_attr_values->attr_values->attribute_value,
            ],
        ];
    }
}
