<?php

namespace App\Http\Resources\Home;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_code' => $this->order->detail->code,
            'status' => $this->status,
            'message' => $this->message,
            'receipt' => $this->receipt,
            'gateway_type' => GatewaysEnum::getTranslations($this->gateway_type, 'نامشخص'),
            'paid_at' => $this->paid_at
                ? vertaTz($this->paid_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
