<?php

namespace App\Http\Requests;

use App\Enums\Products\ProductAttributeTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProductAttributeRequest extends FormRequest
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
            'title' => [
                'sometimes',
                'max:250',
            ],
            'type' => [
                'sometimes',
                new Enum(ProductAttributeTypesEnum::class),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'type' => 'نوع ویژگی',
        ];
    }
}
