<?php

namespace App\Http\Requests;

use App\Models\Province;
use App\Rules\CityInProvinceRule;
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
            'province' => [
                'sometimes',
                'exists:' . Province::class . ',id',
            ],
            'city' => [
                'required_with:province',
                new CityInProvinceRule(),
            ],
            'address' => [
                'sometimes',
                'max:250',
            ],
            'postal_code' => [
                'sometimes',
                'max:15',
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
}
