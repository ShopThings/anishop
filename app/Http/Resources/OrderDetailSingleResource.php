<?php

namespace App\Http\Resources;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderItemShowResource;
use App\Http\Resources\Showing\ReturnOrderShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('user');
        $this->resource->load('send_status_changer');
        $this->resource->load('orders');
        $this->resource->load('orders.payments');
        $this->resource->load('items');
        $this->resource->load('return_order');

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
            'send_status' => [
                'title' => $this->send_status_title,
                'color_hex' => $this->send_status_color_hex,
                'is_starting_badge' => $this->send_status_is_starting_badge,
                'is_end_badge' => $this->send_status_is_end_badge,
            ],
            'send_status_changed_at' => $this->send_status_changed_at
                ? verta($this->send_status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'send_status_changed_by' => new UserShowResource($this->send_status_changer),
            'is_needed_factor' => $this->is_needed_factor,
            'is_in_place_delivery' => $this->is_in_place_delivery,
            'is_product_returned_to_stock' => $this->is_product_returned_to_stock,
            'ordered_at' => $this->ordered_at
                ? verta($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'payment_status' => $this->hasCompletePaid()
                ? [
                    'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS),
                    'value' => PaymentStatusesEnum::SUCCESS->value,
                ]
                : (
                $this->hasAnyPaid()
                    ?
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS),
                        'value' => PaymentStatusesEnum::PARTIAL_SUCCESS->value,
                    ]
                    :
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAYED),
                        'value' => PaymentStatusesEnum::NOT_PAYED->value,
                    ]
                ),
            'orders' => OrderResource::collection($this->orders),
            'order_payments' => GatewayPaymentResource::collection($this->orders->payments),
            'items' => OrderItemShowResource::collection($this->items),
            'return_order' => new ReturnOrderShowResource($this->return_order),
        ];
    }
}
