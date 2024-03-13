<?php

namespace App\Http\Requests;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Rules\FileExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSendMethodRequest extends FormRequest
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
            'image' => [
                'sometimes',
                new FileExistsRule(),
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'priority' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'determine_price_by_shop_location' => [
                'boolean',
            ],
            'only_for_shop_location' => [
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
            'price' => 'هزینه ارسال',
            'determine_price_by_shop_location' => 'در نظرگیری مکان فروشگاه برای قیمت ارسال',
            'only_for_shop_location' => 'اعمال فقط برای محدوده مکان فروشگاه',
        ];
    }
}
