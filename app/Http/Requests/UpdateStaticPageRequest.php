<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaticPageRequest extends FormRequest
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
            'description' => [
                'sometimes',
            ],
            'url' => [
                'sometimes',
                'regex:/[a-z]+[a-z\\\-][a-z]+/i',
            ],
            'keywords' => [
                'sometimes',
                'array',
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
            'url' => 'آدرس صفحه',
        ];
    }
}
