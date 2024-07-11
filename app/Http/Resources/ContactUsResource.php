<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
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
            'message' => $this->message,
            'answer' => $this->answer,
            'is_seen' => $this->is_seen,
            'answered_at' => $this->when(
                $this->answered_at,
                vertaTz($this->answered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'answered_by' => $this->when($this->answered_by, new UserShowResource($this->answeredBy)),
            'changed_status_at' => $this->when(
                $this->changed_status_at,
                vertaTz($this->changed_status_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
//            'changed_status_by' => $this->when($this->changed_status_by, new UserShowResource($this->statusChanger)),
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
