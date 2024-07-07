<?php

namespace App\Http\Resources;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderDetailShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserShowResource($this->whenLoaded('user')),
            'order' => new OrderDetailShowResource($this->whenLoaded('order')),
            'code' => $this->code,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status->value,
                'color_hex' => ReturnOrderStatusesEnum::getStatusColor()[$this->status->value] ?? '#000000',
            ],
            'seen_status' => $this->seen_status,
            'status_changed_at' => $this->status_changed_at
                ? vertaTz($this->status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'status_changed_by' => new UserShowResource($this->when(
                $this->status_changed_by &&
                $this->whenLoaded('statusChanger'),
                $this->resource->status_changer
            )),
            'requested_at' => $this->requested_at
                ? vertaTz($this->requested_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
