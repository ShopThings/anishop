<?php

namespace App\Http\Resources\Home;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogBadgeShowResource;
use App\Http\Resources\Showing\UserBlogShowResource;
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
        $childrenCount = $this->resource->acceptedChildrenCount();
        $creator = $this->creator;

        return [
            'id' => $this->id,
            'badge' => new BlogBadgeShowResource($this->whenLoaded('badge')),
            'comment_id' => $this->comment_id,
            'has_children' => $childrenCount > 0,
            'children_count' => $childrenCount,
            'is_for_current_user' => $this->isCommentForCurrentUser($creator),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition,
            ],
            'description' => $this->description,
            'created_by' => $this->created_by ? new UserBlogShowResource($creator) : null,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT->value)
            ),
        ];
    }

    /**
     * @param $creator
     * @return bool
     */
    protected function isCommentForCurrentUser($creator): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        return $creator->id === $user->id;
    }
}
