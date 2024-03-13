<?php

namespace App\Http\Resources\User;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReturnOrderResource extends JsonResource
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
            'order_code' => $this->order->code,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
                'color_hex' => ReturnOrderStatusesEnum::getStatusColor()[$this->status] ?? '#000000',
            ],
            'requested_at' => $this->requested_at
                ? vertaTz($this->requested_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
        ];
    }
}
