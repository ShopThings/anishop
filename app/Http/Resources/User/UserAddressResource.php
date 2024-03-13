<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Showing\CityShowResource;
use App\Http\Resources\Showing\ProvinceShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('city');
        $this->resource->load('province');

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'city' => new CityShowResource($this->city),
            'province' => new ProvinceShowResource($this->province),
            'postal_code' => $this->postal_code,
        ];
    }
}
