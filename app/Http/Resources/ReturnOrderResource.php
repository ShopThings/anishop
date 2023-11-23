<?php

namespace App\Http\Resources;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user'),
            'order_detail_id' => $this->order_detail_id,
            'code' => $this->code,
            'description' => $this->description,
            'not_accepted_description' => $this->not_accepted_description,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'seen_status' => $this->seen_status,
            'status_changed_at' => $this->status_changed_at,
            'status_changed_by' => $this->status_changed_by,
            'order_detail' => $this->whenLoaded('order'),
            'items' => $this->whenLoaded('returnOrderItems'),
            'responded_at' => $this->when(
                $this->responded_at,
                verta($this->responded_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'responded_by' => $this->when($this->responded_by, $this->responder()),
            'requested_at' => $this->requested_at
                ? verta($this->requested_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'deleted_by' => $this->when($this->deleted_by, $this->deleter()),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
