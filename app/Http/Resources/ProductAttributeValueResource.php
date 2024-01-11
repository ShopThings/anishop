<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductAttributeShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeValueResource extends JsonResource
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
            'priority' => $this->priority,
            'created_by' => new UserShowResource($this->when($this->created_by, $this->creator())),
            'updated_by' => new UserShowResource($this->when($this->updated_by, $this->updater())),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
