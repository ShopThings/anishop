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
        $this->resource->load('returnOrderItems');

        return [
            'id' => $this->id,
            'user' => new UserShowResource($this->user),
            'code' => $this->code,
            'description' => $this->description,
            'not_accepted_description' => $this->not_accepted_description,
            'status' => [
                'text' => ReturnOrderStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'seen_status' => $this->seen_status,
            'status_changed_at' => $this->status_changed_at
                ? verta($this->status_changed_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'status_changed_by' => new UserShowResource($this->when($this->status_changed_by, $this->resource->statusChanger())),
            'order_detail' => new OrderDetailShowResource($this->order),
            'items' => ReturnOrderItemShowResource::collection($this->return_order_items),
            'responded_at' => $this->when(
                $this->responded_at,
                verta($this->responded_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'responded_by' => new UserShowResource($this->when($this->responded_by, $this->resource->responder())),
            'requested_at' => $this->requested_at
                ? verta($this->requested_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
