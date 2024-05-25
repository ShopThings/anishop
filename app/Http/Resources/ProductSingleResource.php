<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BrandShowResource;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\FestivalShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\ProductShowResource;
use App\Http\Resources\Showing\UserShowResource;
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
        $this->resource->load('relatedProducts.product');
        $this->resource->load('creator');
        $this->resource->load('updater');
        $this->resource->load('deleter');

        $user = auth()->user();
        $festival = $this->festivals()->published()->activated()->first();

        return [
            'id' => $this->id,
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
            'festival' => $festival ? new FestivalShowResource($festival) : null,
            'related_products' => ProductShowResource::collection($this->relatedProducts->product),
            'is_favorited' => $this->isFavoritedByUser($user),
            'is_available' => $this->is_available,
            'is_commenting_allowed' => $this->is_commenting_allowed,
            'is_published' => $this->is_published,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }

    /**
     * @param $user
     * @return bool
     */
    protected function isFavoritedByUser($user): bool
    {
        // If user is not authenticated or the product does not have a favoriteProducts relation, return false
        if (!$user || !method_exists($this, 'favoriteProducts')) {
            return false;
        }

        // Check if there is a favorite product record for the current user and this product
        return $this->favoriteProducts()->where('user_id', $user->id)->isNotEmpty();
    }
}
