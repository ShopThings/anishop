<?php

namespace App\Http\Requests;

use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserOrderDetailRequest extends FormRequest
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
            'address' => [
                'sometimes',
                'max:300',
            ],
            'postal_code' => [
                'sometimes',
                'nullable',
                'regex:/[0-9]{10}/',
            ],
            'receiver_name' => [
                'sometimes',
                new PersianNameRule(),
                'max:250',
            ],
            'receiver_mobile' => [
                'sometimes',
                new PersianMobileRule(),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'receiver_name' => 'نام گیرنده',
            'receiver_mobile' => 'شماره گیرنده',
        ];
    }

    public function messages()
    {
        return [
            'postal_code.regex' => 'کدپستی باید برابر ۱۰ رقم باشد.',
        ];
    }
}
