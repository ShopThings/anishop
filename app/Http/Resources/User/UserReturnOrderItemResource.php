<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Showing\ProductShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReturnOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('orderItem');
        $this->resource->load('orderItem.product');
        $this->resource->load('orderItem.product.image');

        return [
            'id' => $this->id,
            'item_id' => $this->order_item_id,
            'return_quantity' => $this->quantity,
            'is_accepted' => $this->accepted_at ? true : null,
            'product' => new ProductShowResource($this->orderItem->product),
            'product_title' => $this->orderItem->product_title,
            'color_name' => $this->orderItem->color_name,
            'color_hex' => $this->orderItem->color_hex,
            'size' => $this->orderItem->size,
            'guarantee' => $this->orderItem->guarantee,
            'price' => $this->orderItem->price,
            'discounted_price' => $this->orderItem->discounted_price,
            'unit_price' => $this->orderItem->unit_price,
            'quantity' => $this->orderItem->quantity,
        ];
    }
}
