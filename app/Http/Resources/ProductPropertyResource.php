<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->idid,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product'),
            'code' => $this->code,
            'color_name' => $this->color_name,
            'color_hex' => $this->color_hex,
            'size' => $this->size,
            'guarantee' => $this->guarantee,
            'weight' => $this->weight,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'discounted_until' => $this->discounted_until,
            'discounted_until_formatted' => $this->discounted_until
                ? verta($this->discounted_until)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'tax_rate' => $this->tax_rate,
            'stock_count' => $this->stock_count,
            'max_cart_count' => $this->max_cart_count,
            'is_special' => $this->is_special,
            'is_available' => $this->is_available,
            'show_coming_soon' => $this->show_coming_soon,
            'show_call_for_more' => $this->show_call_for_more,
            'is_published' => $this->is_published,
        ];
    }
}
