<?php

namespace App\Http\Resources;

use App\Http\Resources\Showing\ProvinceShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('province');

        return [
            'id' => $this->id,
            'province' => new ProvinceShowResource($this->province),
            'name' => $this->name,
            'is_published' => $this->is_published,
        ];
    }
}
