<?php

namespace App\Http\Requests;

use App\Rules\PersianNameRule;
use App\Rules\PersianNationalCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
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
            'first_name' => [
                'sometimes',
                new PersianNameRule(),
            ],
            'last_name' => [
                'sometimes',
                new PersianNameRule(),
            ],
            'national_code' => [
                'sometimes',
                new PersianNationalCodeRule(),
            ],
            'sheba_number' => [
                'sometimes',
                'nullable',
                'regex:/IR\-[0-9]{24}/',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'sheba_number' => 'شماره شبا',
        ];
    }

    public function messages()
    {
        return [
            'sheba_number.regex' => 'شماره شبا باید ۲۴ رقم باشد.',
        ];
    }
}
