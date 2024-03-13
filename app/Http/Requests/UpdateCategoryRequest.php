<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'parent' => [
                'sometimes',
                'nullable',
                'exists:' . Category::class . ',id',
            ],
            'name' => [
                'sometimes',
                'max:250',
            ],
            'priority' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'show_in_menu' => [
                'boolean',
            ],
            'show_in_search_side_menu' => [
                'boolean',
            ],
            'show_in_slider' => [
                'boolean',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'show_in_menu' => 'وضعیت نمایش در منوی اصلی',
            'show_in_search_side_menu' => 'وضعیت نمایش در منوی کنار صفحه جستجوی محصولات',
            'show_in_slider' => 'وضعیت نمایش در اسلایدر',
        ];
    }
}
