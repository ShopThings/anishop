<?php

namespace App\Http\Resources\Showing;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Carbon\Carbon;
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
            'is_waited_for_pay' => $this->payment_status === PaymentStatusesEnum::WAIT->value,
            'must_pay_price' => $this->must_pay_price,
            'payment_method_title' => $this->payment_method_title,
            'payment_method_type' => [
                'text' => PaymentTypesEnum::getTranslations($this->payment_method_type, 'نامشخص'),
                'value' => $this->payment_method_type,
            ],
            'payment_status' => [
                'text' => PaymentStatusesEnum::getTranslations($this->payment_status, 'نامشخص'),
                'value' => $this->payment_status,
                'color_hex' => PaymentStatusesEnum::getStatusColor()[$this->payment_status] ?? '#000000',
            ],
            'payment_status_changed_at' => $this->payment_status_changed_at
                ? vertaTz($this->payment_status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'paid_at' => $this->paid_at
                ? vertaTz($this->paid_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'actual_created_at' => $this->created_at
                ? Carbon::parse($this->created_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
