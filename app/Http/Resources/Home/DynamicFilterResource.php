<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\ProductAttributeValueShowResource;
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
            'id' => $this->productAttr->id,
            'title' => $this->productAttr->title,
            'type' => $this->productAttr->type,
            'values' => ProductAttributeValueShowResource::collection($this->productAttr->attrValues)
        ];
    }
}
