<?php

namespace App\Http\Resources\Home;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
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
            'badge' => $this->whenLoaded('badge'),
            'comment_id' => $this->comment_id,
            'comment' => $this->whenLoaded('parent'),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'description' => $this->description,
            'created_by' => $this->when($this->created_by, $this->creator()),
            'updated_by' => $this->when($this->updated_by, $this->updater()),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT->value)
            ),
        ];
    }
}
