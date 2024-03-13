<?php

namespace App\Http\Requests;

use App\Rules\ColorRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogBadgeRequest extends FormRequest
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
            'color_hex' => [
                'sometimes',
                'max:12',
                new ColorRule(),
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'color_hex' => 'کد رنگ',
        ];
    }
}
