<?php

namespace App\Http\Resources\Showing;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('user');

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
            'postal_code' => $this->postal_code ?? '',
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
            'is_needed_factor' => $this->is_needed_factor,
            'is_product_returned_to_stock' => $this->is_product_returned_to_stock,
            'ordered_at' => $this->ordered_at
                ? vertaTz($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'payment_status' => $this->hasCompletePaid()
                ? [
                    'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS, 'نامشخص'),
                    'value' => PaymentStatusesEnum::SUCCESS->value,
                ]
                : (
                $this->hasAnyPaid()
                    ?
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS, 'نامشخص'),
                        'value' => PaymentStatusesEnum::PARTIAL_SUCCESS->value,
                    ]
                    :
                    [
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAID, 'نامشخص'),
                        'value' => PaymentStatusesEnum::NOT_PAID->value,
                    ]
                ),
        ];
    }
}
