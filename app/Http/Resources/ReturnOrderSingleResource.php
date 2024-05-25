<?php

namespace App\Http\Resources;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\OrderDetailShowResource;
use App\Http\Resources\Showing\ReturnOrderItemShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('user');
        $this->resource->load('order');
        $this->resource->load('statusChanger');
        $this->resource->load('responder');
        $this->resource->load('returnOrderItems');
        $this->resource->load('returnOrderItems.orderItem');

        return [
            'id' => $this->id,
            'user' => new UserShowResource($this->user),
            'code' => $this->code,
            'description' => $this->description,
            'not_accepted_description' => $this->not_accepted_description,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
                'color_hex' => ReturnOrderStatusesEnum::getStatusColor()[$this->status] ?? '#000000',
            ],
            'wait_for_user' => in_array($this->status, ReturnOrderStatusesEnum::getUserStatuses()),
            'is_in_end_status' => in_array($this->status, ReturnOrderStatusesEnum::getEndingStatuses()),
            'seen_status' => $this->seen_status,
            'status_changed_at' => $this->status_changed_at
                ? vertaTz($this->status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'status_changed_by' => new UserShowResource($this->when(
                $this->status_changed_by &&
                $this->whenLoaded('statusChanger'),
                $this->resource->status_changer
            )),
            'order_detail' => new OrderDetailShowResource($this->order),
            'items' => ReturnOrderItemShowResource::collection($this->return_order_items),
            'responded_at' => $this->when(
                $this->responded_at,
                vertaTz($this->responded_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'responded_by' => new UserShowResource($this->when(
                $this->responded_by &&
                $this->whenLoaded('responder'),
                $this->resource->responder
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
