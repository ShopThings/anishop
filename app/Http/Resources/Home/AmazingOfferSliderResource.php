<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\FestivalShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\ProductPropertyShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmazingOfferSliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('image');
        $this->resource->load('items');
        $this->resource->load('festivals.festival');

        $festival = $this->festivals->first()?->festival()->published()->activated()->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => new ImageShowResource($this->image),
            'unit_name' => $this->unit_name,
            'keywords' => $this->keywords,
            'items' => ProductPropertyShowResource::collection($this->items),
            'festival' => $festival ? new FestivalShowResource($festival) : null,
            'is_available' => $this->is_available,
        ];
    }
}
