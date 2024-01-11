<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\BlogCategoryShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Http\Resources\Showing\UserShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogShowResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
