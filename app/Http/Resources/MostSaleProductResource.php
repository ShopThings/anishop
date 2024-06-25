<?php

namespace App\Http\Resources;

use App\Http\Resources\Showing\BrandShowResource;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\ImageShowResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MostSaleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $productModel = Product::query()->where('id', $this->product_id)->first();

        return [
            'id' => $this->product_id,
            'title' => $this->product_title,
            'image' => new ImageShowResource($productModel->image),
            'brand' => new BrandShowResource($productModel->brand),
            'category' => new CategoryShowResource($productModel->category),
            'unit_name' => $this->unit_name,
            'count' => $this->quantity,
        ];
    }
}
