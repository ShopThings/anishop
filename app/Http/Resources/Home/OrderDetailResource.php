<?php

namespace App\Http\Resources\Home;

use App\Enums\Times\TimeFormatsEnum;
use App\Support\Helper\OrderHelper;
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
        $orders = $this->orders;
        $reservationTime = OrderHelper::getReservationTime($orders->count());

        return [
            'code' => $this->code,
            'chunks' => OrderPaymentResource::collection($orders),
            'reservation_time' => $reservationTime,
            'province' => $this->province,
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code ?? '',
            'receiver_name' => $this->receiver_name,
            'receiver_mobile' => $this->receiver_mobile,
            'final_price' => $this->final_price,
            'send_method_title' => $this->send_method_title,
            'is_needed_factor' => $this->is_needed_factor,
            'coupon_code' => $this->coupon_code,
            'coupon_price' => $this->coupon_price,
            'ordered_at' => $this->ordered_at
                ? vertaTz($this->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
