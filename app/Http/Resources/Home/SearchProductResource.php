<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\BrandShowResource;
use App\Http\Resources\Showing\CategoryShowResource;
use App\Http\Resources\Showing\ProductShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchProductResource extends JsonResource
{
    public function __construct($resource, protected $brands, protected $categories)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'products' => ProductShowResource::collection($this->resource),
            'brands' => BrandShowResource::collection($this->brands),
            'categories' => CategoryShowResource::collection($this->categories),
        ];
    }
}
