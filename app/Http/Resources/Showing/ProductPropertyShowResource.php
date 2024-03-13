<?php

namespace App\Http\Resources\Showing;

use App\Enums\Times\TimeFormatsEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPropertyShowResource extends JsonResource
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
            'product_id' => $this->product_id,
            'code' => $this->code,
            'color_name' => $this->color_name,
            'color_hex' => $this->color_hex,
            'size' => $this->size,
            'guarantee' => $this->guarantee,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'discounted_from' => $this->discounted_from
                ? Carbon::parse($this->discounted_from)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'discounted_until' => $this->discounted_until
                ? Carbon::parse($this->discounted_until)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
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
