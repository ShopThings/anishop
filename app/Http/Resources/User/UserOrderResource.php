<?php

namespace App\Http\Resources\User;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'send_status' => [
                'title' => $this->send_status_title,
                'color_hex' => $this->send_status_color_hex,
                'is_starting_badge' => $this->send_status_is_starting_badge,
                'is_end_badge' => $this->send_status_is_end_badge,
            ],
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
                        'text' => PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAYED, 'نامشخص'),
                        'value' => PaymentStatusesEnum::NOT_PAYED->value,
                        'color_hex' => PaymentStatusesEnum::getStatusColor()[PaymentStatusesEnum::NOT_PAYED->value] ?? '#000000',
                    ]
                ),
            'final_price' => $this->final_price,
            'ordered_at' => $this->ordered_at
                ? vertaTz($this->ordered_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
        ];
    }
}
