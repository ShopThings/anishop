<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\ProductShowResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = Product::query()->where('id', $this->product_id)->first();

        if (!$product instanceof Model) {
            return [];
        }

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'price' => $this->price,
            'tax_rate' => $this->tax_rate,
            'actual_price' => $this->actual_price,
            'quantity' => $this->qty,
            'color_name' => $this->color_name,
            'color_hex' => $this->color_hex,
            'size' => $this->size,
            'guarantee' => $this->guarantee,
            'stock_count' => $this->stock_count,
            'max_cart_count' => $this->max_cart_count,
            'is_special' => $this->is_special,
            'has_separate_shipment' => $this->has_separate_shipment,
            'product' => new ProductShowResource($product),
        ];
    }
}
