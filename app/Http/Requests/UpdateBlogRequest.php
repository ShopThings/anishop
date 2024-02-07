<?php

namespace App\Http\Requests;

use App\Models\BlogCategory;
use App\Rules\FileExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'category' => [
                'sometimes',
                'exists:' . BlogCategory::class . ',id',
            ],
            'title' => [
                'sometimes',
                'max:250',
            ],
            'image' => [
                'sometimes',
                new FileExistsRule(),
            ],
            'description' => [
                'sometimes',
            ],
            'keywords' => [
                'sometimes',
                'array',
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
            'is_commenting_allowed' => 'اجازه ارسال دیدگاه',
        ];
    }
}
