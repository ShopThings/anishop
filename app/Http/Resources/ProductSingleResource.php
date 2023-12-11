<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSingleResource extends JsonResource
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
            'brand_id' => $this->brand_id,
            'brand' => $this->whenLoaded('brand'),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'title' => $this->title,
            'escaped_title' => $this->escaped_title,
            'slug' => $this->slug,
            'image_id' => $this->image_id,
            'image' => $this->whenLoaded('image'),
            'gallery_images' => $this->whenLoaded('images'),
            'description' => $this->description,
            'properties' => $this->properties,
            'quick_properties' => $this->quick_properties,
            'unit_name' => $this->unit_name,
            'keywords' => $this->keywords,
            'items' => $this->whenLoaded('items'),
            'festivals' => $this->whenLoaded('festivals'),
            'related_products' => $this->whenLoaded('relatedProducts'),
            'product_attr_values' => $this->whenLoaded('productAttrValues'),
            'favorite_products' => $this->whenLoaded('favoriteProducts'),
            'is_available' => $this->is_available,
            'is_commenting_allowed' => $this->is_commenting_allowed,
            'is_published' => $this->is_published,
            'created_by' => $this->when($this->created_by, $this->creator()),
            'updated_by' => $this->when($this->updated_by, $this->updater()),
            'deleted_by' => $this->when($this->deleted_by, $this->deleter()),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
