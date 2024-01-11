<?php

namespace App\Http\Resources\Home;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchiveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year' => verta()->month($this->year)->format('Y'),
            'month' => verta()->month($this->month)->format('F'),
            'count' => $this->count,
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::ARCHIVE->value)
                : null,
        ];
    }
}
