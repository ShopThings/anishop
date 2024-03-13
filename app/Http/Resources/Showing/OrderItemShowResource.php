<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('product.image');

        return [
            'id' => $this->id,
            'product' => new ProductShowResource($this->whenLoaded('product')),
            'product_code' => $this->product_code,
            'product_title' => $this->product_title,
            'color_name' => $this->color_name,
            'color_hex' => $this->color_hex,
            'size' => $this->size,
            'guarantee' => $this->guarantee,
            'weight' => $this->weight,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'unit_name' => $this->unit_name,
            'is_returned' => $this->is_returned,
            'image' => new ImageShowResource($this->product->image),
        ];
    }
}
