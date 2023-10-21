<?php

namespace App\Http\Requests;

use App\Rules\ColorRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
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
            'hex' => [
                'sometimes',
                'max:12',
                new ColorRule(),
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
            'hex' => 'کد رنگ',
        ];
    }
}
