<?php

namespace App\Http\Resources\Showing;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentShowResource extends JsonResource
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
            'blog' => new BlogShowResource($this->whenLoaded('blog')),
            'badge' => new BlogBadgeShowResource($this->whenLoaded('badge')),
            'comment_id' => $this->comment_id,
            'has_children' => $this->resource->hasChildren(),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
            ],
            'description' => $this->description,
            'created_by' => $this->created_by ? new UserCommentShowResource($this->creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
