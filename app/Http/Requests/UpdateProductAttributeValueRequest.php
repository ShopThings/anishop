<?php

namespace App\Http\Requests;

use App\Models\ProductAttributeValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductAttributeValueRequest extends FormRequest
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
            'product_attribute' => [
                'sometimes',
                'exists:' . ProductAttributeValue::class . ',id',
            ],
            'attribute_value' => [
                'sometimes',
                'max:250',
            ],
            'priority' => [
                'sometimes',
                'numeric',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'product_attribute' => 'ویژگی محصول',
            'attribute_value' => 'مثدار ویژگی',
        ];
    }
}
