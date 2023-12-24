<?php

namespace App\Http\Resources;

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
            'blog_id' => $this->blog_id,
            'blog' => $this->whenLoaded('blog'),
            'badge_id' => $this->badge_id,
            'badge' => $this->whenLoaded('badge'),
            'comment_id' => $this->comment_id,
            'comment' => $this->whenLoaded('parent'),
            'all_children' => $this->whenLoaded('allChildren'),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'description' => $this->description,
            'flag_count' => $this->flag_count,
            'created_by' => $this->when($this->created_by, $this->creator()),
            'updated_by' => $this->when($this->updated_by, $this->updater()),
            'deleted_by' => $this->when($this->deleted_by, $this->deleter()),
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
