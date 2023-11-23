<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightPostPriceRequest extends FormRequest
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
            'min_weight' => [
                'sometimes',
                'numeric',
                'min:0',
                'lt:max_weight',
            ],
            'max_weight' => [
                'sometimes',
                'numeric',
                'min:0',
                'gt:min_weight',
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
            'min_weight' => 'حداقل وزن مرسولات',
            'max_weight' => 'حداکثر وزن مرسولات',
            'post_price' => 'هزینه ارسال',
        ];
    }
}
