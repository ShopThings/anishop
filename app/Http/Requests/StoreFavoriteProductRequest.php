<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteProductRequest extends FormRequest
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
            'product' => [
                'required',
                'exists:' . Product::class . ',id',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'product' => 'محصول جهت افزودن به لیست علاقه‌مندی‌ها',
        ];
    }
}
