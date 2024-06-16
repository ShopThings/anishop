<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\UserShowResource;
use App\Traits\CompanyTimezoneDetectorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FestivalResource extends JsonResource
{
    use CompanyTimezoneDetectorTrait;

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
            'slug' => $this->slug,
            'actual_start_at' => $this->start_at
                ? Carbon::parse($this->start_at)
                    ->timezone($this->getCompanyTimezone())
                    ->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'actual_end_at' => $this->end_at
                ? Carbon::parse($this->end_at)
                    ->timezone($this->getCompanyTimezone())
                    ->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'start_at' => $this->start_at
                ? vertaTz($this->start_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'end_at' => $this->end_at
                ? vertaTz($this->end_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'is_published' => $this->is_published,
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
            'created_at' => $this->created_at
                ? vertaTz($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
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
