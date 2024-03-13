<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductAttributeCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attribute' => [
                'sometimes',
                'exists:' . ProductAttribute::class . ',id',
            ],
            'category' => [
                'sometimes',
                'exists:' . Category::class . ',id',
            ],
            'priority' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'attribute' => 'ویژگی محصول',
        ];
    }
}
