<?php

namespace App\Http\Requests;

use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMultiProductPrice extends FormRequest
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
            'ids' => [
                'required',
                'array',
                'min:1',
            ],
            'price_percentage' => [
                'required',
                'min:0',
                'max:100',
            ],
            'change_type' => [
                'required',
                new Enum(ChangeMultipleProductPriceTypesEnum::class),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'ids' => 'شناسه محصولات',
            'price_percentage' => 'درصد تغییر قیمت',
            'change_type' => 'نوع تغییر قیمت',
        ];
    }
}
