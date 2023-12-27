<?php

namespace App\Http\Resources\User;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReturnOrderSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('order');
        $this->resource->load('returnOrderItems');
        $this->resource->load('returnOrderItems.orderItem');
        $this->resource->load('returnOrderItems.orderItem.product');
        $this->resource->load('returnOrderItems.orderItem.product.image');

        return [
            'code' => $this->code,
            'order_code' => $this->order->code,
            'description' => $this->description,
            'not_accepted_description' => $this->not_accepted_description,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'seen_status' => $this->seen_status,
            'items' => $this->whenLoaded('returnOrderItems', function () {
                $item = $this->order_item;

                return [
                    'id' => $this->id,
                    'item_id' => $this->order_item_id,
                    'return_quantity' => $this->quantity,
                    'is_accepted' => $this->accepted_at ? true : null,
                    'product_title' => $item->product_title,
                    'color_name' => $item->color_name,
                    'color_hex' => $item->color_hex,
                    'size' => $item->size,
                    'guarantee' => $item->guarantee,
                    'price' => $item->price,
                    'discounted_price' => $item->discounted_price,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'image' => [
                        'path' => $item->product->image->full_path,
                    ],
                ];
            }),
        ];
    }
}
