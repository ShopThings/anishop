<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FileManager;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return [
            'brand' => [
                'sometimes',
                'exists:' . Brand::class . ',id',
            ],
            'category' => [
                'sometimes',
                'exists:' . Category::class . ',id',
            ],
            'title' => [
                'sometimes',
                'max:250',
            ],
            'image' => [
                'sometimes',
                'exists:' . FileManager::class . ',id',
            ],
            'properties' => [
                'sometimes',
                'array',
            ],
            'quick_properties' => [
                'sometimes',
                'array',
            ],
            'unit' => [
                'sometimes',
                'exists:' . Unit::class . ',id',
            ],
            'description' => [
                'sometimes',
            ],
            'keywords' => [
                'sometimes',
                'array',
            ],
            'is_available' => [
                'sometimes',
                'boolean',
            ],
            'is_commenting_allowed' => [
                'sometimes',
                'boolean',
            ],
            'is_published' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'quick_properties' => 'ویژگی‌های سریع',
            'unit' => 'واحد محصول',
            'is_commenting_allowed' => 'اجازه ارسال نظر',
        ];
    }
}
