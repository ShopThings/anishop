<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'path' => $this->full_path,
        ];
    }
}
