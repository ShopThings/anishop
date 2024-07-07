<?php

namespace App\Http\Resources\User;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProductCommentSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('product');

        return [
            'id' => $this->id,
            'product' => new ProductShowResource($this->product),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition->value,
            ],
            'pros' => $this->pros,
            'cons' => $this->cons,
            'description' => $this->description,
            'answer' => $this->answer,
            'is_condition_changed' => !!$this->changed_condition_at,
            'up_vote_count' => $this->up_vote_count,
            'down_vote_count' => $this->down_vote_count,
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
        ];
    }
}
