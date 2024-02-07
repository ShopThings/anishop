<?php

namespace App\Http\Resources\Showing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageShowInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_path' => $this->full_path,
            'name' => $this->name,
            'extension' => $this->extension,
            'is_dir' => false,
        ];
    }
}
