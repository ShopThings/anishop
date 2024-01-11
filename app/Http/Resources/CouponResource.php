<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'title' => $this->title,
            'code' => $this->code,
            'price' => $this->price,
            'apply_min_price' => $this->apply_min_price,
            'apply_max_price' => $this->apply_max_price,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'use_count' => $this->use_count,
            'reusable_after' => $this->reusable_after,
            'is_published' => $this->is_published,
            'created_by' => new UserShowResource($this->when($this->created_by, $this->creator())),
            'updated_by' => new UserShowResource($this->when($this->updated_by, $this->updater())),
            'deleted_by' => new UserShowResource($this->when($this->deleted_by, $this->deleter())),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
