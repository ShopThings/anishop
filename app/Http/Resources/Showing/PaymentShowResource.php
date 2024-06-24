<?php

namespace App\Http\Resources\Showing;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'receipt' => $this->receipt,
            'gateway_type' => [
                'text' => $this->gateway_type ?? 'نامشخص',
                'value' => GatewaysEnum::getTranslations($this->gateway_type ?? '', 'نامشخص'),
            ],
            'paid_at' => $this->paid_at
                ? vertaTz($this->paid_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
