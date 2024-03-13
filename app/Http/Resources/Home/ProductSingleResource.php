<?php

namespace App\Http\Resources\Home;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\ProductPropertyResource;
use App\Http\Resources\Showing\BrandShowResource;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\FestivalShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\ProductAttributeValueShowResource;
use App\Http\Resources\Showing\ProductShowResource;
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
        $this->resource->load('brand');
        $this->resource->load('category');
        $this->resource->load('image');
        $this->resource->load('images');
        $this->resource->load('items');
        $this->resource->load('festivals');
        $this->resource->load('relatedProducts.product');
        $this->resource->load('productAttrValues');
        $this->resource->load('favoriteProducts.product');

        return [
            'brand_id' => $this->brand_id,
            'brand' => new BrandShowResource($this->brand),
            'category_id' => $this->category_id,
            'category' => new CategoryShowResource($this->category),
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->image),
            'gallery_images' => ImageShowResource::collection($this->images),
            'description' => $this->description,
            'properties' => $this->properties,
            'quick_properties' => $this->quick_properties,
            'unit_name' => $this->unit_name,
            'keywords' => $this->keywords,
            'items' => ProductPropertyResource::collection($this->items),
            'festivals' => FestivalShowResource::collection($this->festivals),
            'related_products' => ProductShowResource::collection($this->related_products->product),
            'product_attr_values' => ProductAttributeValueShowResource::collection($this->product_attr_values),
            'favorite_products' => ProductShowResource::collection($this->favorite_products->product),
            'is_available' => $this->is_available,
            'is_commenting_allowed' => $this->is_commenting_allowed,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
