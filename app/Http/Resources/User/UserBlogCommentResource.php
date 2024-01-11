<?php

namespace App\Http\Resources\User;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogBadgeShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBlogCommentResource extends JsonResource
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
            'blog' => $this->whenLoaded('blog', function () {
                return [
                    'id' => $this->blog->id,
                    'slug' => $this->blog->slug,
                    'image' => [
                        'path' => $this->blog->image->full_path,
                    ],
                ];
            }),
            'badge' => new BlogBadgeShowResource($this->badge),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status),
                'value' => $this->status,
            ],
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
