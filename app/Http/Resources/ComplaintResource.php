<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'name' => $this->name,
            'mobile' => $this->mobile,
            'description' => $this->description,
            'is_seen' => $this->is_seen,
            'changed_status_at' => $this->when(
                $this->changed_status_at,
                vertaTz($this->changed_status_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'changed_status_by' => new UserShowResource($this->when($this->changed_status_by, $this->status_changer)),
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'deleted_at' => $this->when(
                $this->deleted_at,
                vertaTz($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
