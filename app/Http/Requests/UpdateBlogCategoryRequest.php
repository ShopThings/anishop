<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogCategoryRequest extends FormRequest
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
            'name' => [
                'sometimes',
                'max:250',
            ],
            'priority' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'keywords' => [
                'sometimes',
                'array',
            ],
            'is_published' => [
                'boolean',
            ],
            'show_in_menu' => [
                'boolean',
            ],
            'show_in_side_menu' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'show_in_menu' => 'نمایش در منوی اصلی',
            'show_in_side_menu' => 'نمایش در منوی کناری',
        ];
    }
}
