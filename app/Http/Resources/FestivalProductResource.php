<?php

namespace App\Http\Resources;

use App\Http\Resources\Showing\FestivalShowResource;
use App\Http\Resources\Showing\ProductShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FestivalProductResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product' => new ProductShowResource($this->whenLoaded('product')),
            'festival_id' => $this->festival_id,
            'festival' => new FestivalShowResource($this->whenLoaded('festival')),
            'discount_percentage' => $this->discount_percentage,
        ];
    }
}
