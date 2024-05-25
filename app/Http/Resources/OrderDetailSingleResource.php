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
        $this->resource->load('sendStatusChanger');
        $this->resource->load('orders');
        $this->resource->load('orders.paymentStatusChanger');
        $this->resource->load('orders.payments');
        $this->resource->load('items');
        $this->resource->load('returnOrder');

        return [
            'id' => $this->id,
            'user' => new UserShowResource($this->user),
            'code' => $this->code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'national_code' => $this->national_code,
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
                ? vertaTz($this->send_status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'send_status_changed_by' => new UserShowResource($this->send_status_changer),
            'is_needed_factor' => $this->is_needed_factor,
            'is_product_returned_to_stock' => $this->is_product_returned_to_stock,
            'ordered_at' => $this->ordered_at
                ? vertaTz($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'payment_status' => $this->hasCompletePaid()
                ? [
                    'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS, 'نامشخص'),
                    'value' => PaymentStatusesEnum::SUCCESS->value,
                    'color_hex' => PaymentStatusesEnum::getStatusColor()[PaymentStatusesEnum::SUCCESS->value] ?? '#000000',
                ]
                : (
                $this->hasAnyPaid()
                    ?
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS, 'نامشخص'),
                        'value' => PaymentStatusesEnum::PARTIAL_SUCCESS->value,
                        'color_hex' => PaymentStatusesEnum::getStatusColor()[PaymentStatusesEnum::PARTIAL_SUCCESS->value] ?? '#000000',
                    ]
                    :
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAID, 'نامشخص'),
                        'value' => PaymentStatusesEnum::NOT_PAID->value,
                        'color_hex' => PaymentStatusesEnum::getStatusColor()[PaymentStatusesEnum::NOT_PAID->value] ?? '#000000',
                    ]
                ),
            'orders' => OrderResource::collection($this->orders),
            'items' => OrderItemShowResource::collection($this->items),
            'return_order' => new ReturnOrderShowResource($this->return_order),
        ];
    }
}
