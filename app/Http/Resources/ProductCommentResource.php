<?php

namespace App\Http\Resources;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCommentResource extends JsonResource
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
            'product' => new ProductShowResource($this->product),
            'condition' => [
                'text' => CommentConditionsEnum::getTranslations($this->condition, 'نامشخص'),
                'value' => $this->condition,
            ],
            'status' => [
                'text' => CommentStatusesEnum::getTranslations($this->status, 'نامشخص'),
                'value' => $this->status,
            ],
            'pros' => $this->pros,
            'cons' => $this->cons,
            'description' => $this->description,
            'answered_at' => $this->when(
                $this->answered_at,
                vertaTz($this->answered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'answered_by' => new UserShowResource($this->when($this->answered_by, $this->answered_by)),
            'changed_condition_at' => $this->when(
                $this->changed_condition_at,
                vertaTz($this->changed_condition_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'changed_condition_by' => new UserShowResource($this->when($this->changed_condition_by, $this->condition_changer)),
            'flag_count' => $this->flag_count,
            'up_vote_count' => $this->up_vote_count,
            'down_vote_count' => $this->down_vote_count,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value),
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
