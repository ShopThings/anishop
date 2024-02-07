<?php

namespace App\Http\Requests;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Rules\FileExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePaymentMethodRequest extends FormRequest
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
            'image' => [
                'sometimes',
                new FileExistsRule(),
            ],
            'type' => [
                'sometimes',
                new Enum(PaymentTypesEnum::class),
            ],
            'bank_gateway_type' => [
                'sometimes',
                new Enum(GatewaysEnum::class),
            ],
            'options' => [
                'sometimes',
                'array',
                'nullable',
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
            'type' => 'نوع روش پرداخت',
            'bank_gateway_type' => 'نوع درگاه',
            'options' => 'اطلاعات تکمیلی درگاه',
        ];
    }
}
