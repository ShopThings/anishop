<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ImageShowInfoResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SendMethodResource extends JsonResource
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
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'image' => new ImageShowInfoResource($this->image),
            'determine_price_by_shop_location' => $this->determine_price_by_shop_location,
            'only_for_shop_location' => $this->only_for_shop_location,
            'is_published' => $this->is_published,
            'is_deletable' => $this->is_deletable,
            'price' => $this->price,
            'priority' => $this->priority,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
