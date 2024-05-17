<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Showing\BlogCategoryShowResource;
use App\Http\Resources\Showing\BlogShowResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchBlogResource extends JsonResource
{
    public function __construct($resource, protected $categories)
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
            'blogs' => BlogShowResource::collection($this->resource),
            'categories' => BlogCategoryShowResource::collection($this->categories),
        ];
    }
}
