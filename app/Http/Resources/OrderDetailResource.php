<?php

namespace App\Http\Resources;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderItemShowResource;
use App\Http\Resources\Showing\ReturnOrderShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'user' => new UserShowResource($this->whenLoaded('user')),
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
            'send_status_changed_by' => new UserShowResource($this->whenLoaded('send_status_changer')),
            'is_needed_factor' => $this->is_needed_factor,
            'is_in_place_delivery' => $this->is_in_place_delivery,
            'is_product_returned_to_stock' => $this->is_product_returned_to_stock,
            'ordered_at' => $this->ordered_at
                ? verta($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'payment_status' => $this->hasCompletePaid()
                ? PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS)
                : (
                $this->hasAnyPaid()
                    ? PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS)
                    : PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAYED)
                ),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'order_payments' => $this->when(
                $this->whenLoaded('orders') &&
                $this->orders->relationLoaded('payments'),
                function () {
                    return $this->orders->payments?->id
                        ? $this->orders->payments
                        : null;
                }
            ),
            'items' => OrderItemShowResource::collection($this->$this->whenLoaded('items')),
            'return_order' => new ReturnOrderShowResource($this->whenLoaded('return_order')),
        ];
    }
}
