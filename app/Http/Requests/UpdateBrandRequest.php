<?php

namespace App\Http\Requests;

use App\Rules\FileExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
                'max:250'
            ],
            'latin_name' => [
                'sometimes',
                'max:250',
            ],
            'image' => [
                'sometimes',
                new FileExistsRule(),
            ],
            'keywords' => [
                'sometimes',
                'array',
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
            'show_in_slider' => 'وضعیت نمایش در اسلایدر',
        ];
    }
}
