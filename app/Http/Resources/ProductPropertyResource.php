<?php

namespace App\Http\Resources;

use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ProductShowResource;
use App\Traits\CompanyTimezoneDetectorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPropertyResource extends JsonResource
{
    use CompanyTimezoneDetectorTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->load('product');

        $productFestival = $this->product()->withWhereHas('festivals.festival', function ($query) {
            $query->published()->activated();
        })->first();

        $festivalDiscountedFrom = null;
        $festivalDiscountedUntil = null;

        if (!is_null($productFestival)) {
            $festival = $productFestival->festivals->first()?->festival;
            $festivalDiscountedFrom = $festival?->start_at;
            $festivalDiscountedUntil = $festival?->end_at;
        }

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product' => new ProductShowResource($this->whenLoaded('product')),
            'code' => $this->code,
            'color_name' => $this->color_name,
            'color_hex' => $this->color_hex,
            'size' => $this->size,
            'guarantee' => $this->guarantee,
            'weight' => $this->weight,
            'buyable_price' => $this->getBuyablePrice(),
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'normal_discounted_from' => $this->discounted_from
                ? Carbon::parse($this->discounted_from)
                    ->timezone($this->getCompanyTimezone())
                    ->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'normal_discounted_until' => $this->discounted_until
                ? Carbon::parse($this->discounted_until)
                    ->timezone($this->getCompanyTimezone())
                    ->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'discounted_from_in_seconds' => $this->discounted_from
                ? vertaTz($this->discounted_from)->diffSeconds(now())
                : 0,
            'discounted_until_in_seconds' => $this->discounted_until
                ? vertaTz($this->discounted_until)->diffSeconds(now())
                : 0,
            'discounted_from' => $this->discounted_from
                ? vertaTz($this->discounted_from)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'discounted_until' => $this->discounted_until
                ? vertaTz($this->discounted_until)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'normal_festival_discounted_from' => $festivalDiscountedFrom
                ? vertaTz($festivalDiscountedFrom)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'normal_festival_discounted_until' => $festivalDiscountedUntil
                ? vertaTz($festivalDiscountedUntil)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            'festival_discounted_from_in_seconds' => $festivalDiscountedFrom
                ? vertaTz($festivalDiscountedFrom)->diffSeconds(now())
                : 0,
            'festival_discounted_until_in_seconds' => $festivalDiscountedUntil
                ? vertaTz($festivalDiscountedUntil)->diffSeconds(now())
                : 0,
            'festival_discounted_from' => $festivalDiscountedFrom
                ? vertaTz($festivalDiscountedFrom)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'festival_discounted_until' => $festivalDiscountedUntil
                ? vertaTz($festivalDiscountedUntil)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'tax_rate' => $this->tax_rate,
            'stock_count' => $this->stock_count,
            'max_cart_count' => $this->max_cart_count,
            'is_special' => $this->is_special,
            'is_available' => $this->is_available,
            'show_coming_soon' => $this->show_coming_soon,
            'show_call_for_more' => $this->show_call_for_more,
            'is_published' => $this->is_published,
            'has_separate_shipment' => $this->has_separate_shipment,
            'updated_at' => $this->when(
                $this->updated_at,
                vertaTz($this->updated_at)->format(TimeFormatsEnum::DEFAULT->value)
            ),
        ];
    }
}
