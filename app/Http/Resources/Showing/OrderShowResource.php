<?php

namespace App\Http\Resources\Showing;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\PaymentShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowResource extends JsonResource
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
            'payments' => PaymentShowResource::collection($this->whenLoaded('payments')),
            'has_paid' => $this->resource->hasPaid(),
            'must_pay_price' => $this->must_pay_price,
            'payment_method_title' => $this->payment_method_title,
            'payment_method_type' => [
                'text' => $this->payment_method_type,
                'value' => PaymentTypesEnum::getTranslations($this->payment_method_type),
            ],
            'payment_status' => [
                'text' => $this->payment_status,
                'value' => PaymentStatusesEnum::getTranslations($this->payment_status),
            ],
            'payed_at' => $this->payed_at
                ? verta($this->payed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
