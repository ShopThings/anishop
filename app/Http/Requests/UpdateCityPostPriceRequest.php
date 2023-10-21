<?php

namespace App\Http\Requests;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCityPostPriceRequest extends FormRequest
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
            'city' => [
                'sometimes',
                'exists:' . City::class . ',id',
            ],
            'post_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'post_price' => 'هزینه ارسال',
        ];
    }
}
