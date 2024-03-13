<?php

namespace App\Http\Resources\Home;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'pros' => $this->pros,
            'cons' => $this->cons,
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
            ],
            'description' => $this->description,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT->value)
            ),
        ];
    }
}
