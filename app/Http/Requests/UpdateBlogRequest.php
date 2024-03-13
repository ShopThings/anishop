<?php

namespace App\Http\Requests;

use App\Models\BlogCategory;
use App\Rules\FileExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $model = $this->route('blog');
        $blogId = $model->id;

        return [
            'category' => [
                'sometimes',
                'exists:' . BlogCategory::class . ',id',
            ],
            'title' => [
                'sometimes',
                'max:250',
                Rule::unique('blogs', 'title')->ignore($blogId),
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
            'is_commenting_allowed' => 'اجازه ارسال دیدگاه',
        ];
    }
}
