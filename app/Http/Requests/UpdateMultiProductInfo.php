<?php

namespace App\Http\Requests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMultiProductInfo extends FormRequest
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
            'ids' => [
                'required',
                'array',
                'min:1',
            ],
            'unit' => [
                'sometimes',
                'exists:' . Unit::class . ',id',
            ],
            'brand' => [
                'sometimes',
                'exists:' . Brand::class . ',id',
            ],
            'category' => [
                'sometimes',
                'exists:' . Category::class . ',id',
            ],
            'is_available' => [
                'boolean',
            ],
            'is_published' => [
                'boolean',
            ],
            'is_commenting_allowed' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'ids' => 'شناسه محصولات',
            'unit' => 'واحد شمارش محصول',
            'is_available' => 'وضعیت موجود بودن',
            'is_published' => 'نمایش محصول در سایت',
            'is_commenting_allowed' => 'اجازه ارسال دیدگاه',
        ];
    }
}
