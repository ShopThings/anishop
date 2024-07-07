<?php

namespace App\Http\Resources;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'payments' => GatewayPaymentResource::collection($this->whenLoaded('payments')),
            'must_pay_price' => $this->must_pay_price,
            'payment_method_title' => $this->payment_method_title,
            'payment_method_type' => [
                'text' => PaymentTypesEnum::getTranslations($this->payment_method_type, 'نامشخص'),
                'value' => $this->payment_method_type->value,
            ],
            'payment_status' => [
                'text' => PaymentStatusesEnum::getTranslations($this->payment_status, 'نامشخص'),
                'value' => $this->payment_status->value,
                'color_hex' => PaymentStatusesEnum::getStatusColor()[$this->payment_status->value] ?? '#000000',
            ],
            'payment_status_changed_at' => $this->payment_status_changed_at
                ? vertaTz($this->payment_status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'payment_status_changed_by' => new UserShowResource($this->whenLoaded('paymentStatusChanger')),
            'paid_at' => $this->paid_at
                ? vertaTz($this->paid_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
