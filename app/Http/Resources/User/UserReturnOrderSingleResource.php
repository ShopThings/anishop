<?php

namespace App\Http\Resources\User;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReturnOrderSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('order');
        $this->resource->load('returnOrderItems');

        return [
            'code' => $this->code,
            'order_code' => $this->order->code,
            'description' => $this->description,
            'admin_description' => $this->admin_description,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
                'color_hex' => ReturnOrderStatusesEnum::getStatusColor()[$this->status->value] ?? '#000000',
            ],
            'has_status_changed' => !!$this->status_changed_at,
            'next_status' => $this->when($this->status->value === ReturnOrderStatusesEnum::ACCEPT->value, function () {
                return [
                    'text' => 'در حال ارسال مرسولات',
                    'value' => ReturnOrderStatusesEnum::SENDING->value,
                ];
            }, $this->when($this->status->value === ReturnOrderStatusesEnum::RETURN_TO_USER->value, function () {
                return [
                    'text' => 'دریافت محصولات بازگشت داده شده',
                    'value' => ReturnOrderStatusesEnum::RECEIVED_BY_USER->value,
                ];
            })),
            'items' => UserReturnOrderItemResource::collection($this->returnOrderItems),
        ];
    }
}
