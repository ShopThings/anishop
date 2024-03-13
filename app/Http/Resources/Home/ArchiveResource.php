<?php

namespace App\Http\Resources\Home;

use App\Enums\Times\TimeFormatsEnum;
use Carbon\Carbon;
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
            'year' => vertaTz()->year($this->year)->format('Y'),
            'month' => vertaTz()->month($this->month)->format('F'),
            'count' => $this->count,
            'created_at' => vertaTz(Carbon::createFromDate($this->year, $this->month))
                ->format(TimeFormatsEnum::ARCHIVE->value)
        ];
    }
}
