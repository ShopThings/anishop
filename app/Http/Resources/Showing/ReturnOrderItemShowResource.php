<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderItemShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('orderItem');

        return [
            'id' => $this->id,
            'item_id' => $this->order_item_id,
            'return_quantity' => $this->quantity,
            'is_accepted' => $this->accepted_at ? true : null,
            'order_item' => new OrderItemShowResource($this->order_item),
        ];
    }
}
