<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductCommentRequest extends FormRequest
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
            'pros' => [
                'sometimes',
                'array',
            ],
            'cons' => [
                'sometimes',
                'array',
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'pros' => 'مزایای محصول',
            'cons' => 'معایب محصول',
        ];
    }
}
