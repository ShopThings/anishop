<?php

namespace App\Http\Requests;

use App\Enums\Payments\PaymentStatusesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateOrderRequest extends FormRequest
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
            'payment_status' => [
                'sometimes',
                new Enum(PaymentStatusesEnum::class),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'payment_status' => 'وضعیت پرداخت',
        ];
    }
}
