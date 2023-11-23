<?php

namespace App\Http\Requests;

use App\Models\FileManager;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
                'required',
                'max:250'
            ],
            'latin_name' => [
                'required',
                'max:250',
            ],
            'image_id' => [
                'required',
                'exists:' . FileManager::class . ',id',
            ],
            'keywords' => [
                'array',
            ],
            'show_in_slider' => [
                'required',
                'boolean',
            ],
            'is_published' => [
                'required',
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
