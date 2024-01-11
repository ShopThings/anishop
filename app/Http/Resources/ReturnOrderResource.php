<?php

namespace App\Http\Resources;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderShowResource;
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
            'order' => new OrderShowResource($this->whenLoaded('order')),
            'code' => $this->code,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'seen_status' => $this->seen_status,
            'status_changed_at' => $this->status_changed_at
                ? verta($this->status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'status_changed_by' => new UserShowResource($this->status_changed_by),
            'requested_at' => $this->requested_at
                ? verta($this->requested_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'deleted_by' => new UserShowResource($this->when($this->deleted_by, $this->deleter())),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
