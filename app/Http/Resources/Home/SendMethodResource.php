<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\ImageShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SendMethodResource extends JsonResource
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
            'description' => $this->description,
            'image' => new ImageShowResource($this->image),
            'only_for_shop_location' => $this->only_for_shop_location,
            'determine_price_by_shop_location' => $this->determine_price_by_shop_location,
            'apply_number_of_shipments_on_price' => $this->apply_number_of_shipments_on_price,
            'price' => $this->price,
        ];
    }
}
