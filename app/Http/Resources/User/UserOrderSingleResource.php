<?php

namespace App\Http\Resources\User;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderItemShowResource;
use App\Http\Resources\Showing\OrderShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('user');
        $this->resource->load('orders');
        $this->resource->load('orders.payments');
        $this->resource->load('items');

        return [
            'id' => $this->id,
            'user' => new UserShowResource($this->user),
            'code' => $this->code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mobile' => $this->mobile,
            'province' => $this->province,
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'receiver_name' => $this->receiver_name,
            'receiver_mobile' => $this->receiver_mobile,
            'description' => $this->description,
            'coupon_code' => $this->coupon_code,
            'coupon_price' => $this->coupon_price,
            'shipping_price' => $this->shipping_price,
            'discount_price' => $this->discount_price,
            'final_price' => $this->final_price,
            'total_price' => $this->total_price,
            'send_status_title' => $this->send_status_title,
            'send_status_color_hex' => $this->send_status_color_hex,
            'send_status_changed_at' => $this->send_status_changed_at
                ? verta($this->send_status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'is_needed_factor' => $this->is_needed_factor,
            'is_in_place_delivery' => $this->is_in_place_delivery,
            'ordered_at' => $this->ordered_at
                ? verta($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'orders' => OrderShowResource::collection($this->orders),
            'items' => OrderItemShowResource::collection($this->items),
        ];
    }
}
