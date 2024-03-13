<?php

namespace App\Http\Requests;

use App\Models\Province;
use App\Rules\CityInProvinceRule;
use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'full_name' => [
                'sometimes',
                new PersianNameRule(),
                'max:150',
            ],
            'mobile' => [
                'sometimes',
                new PersianMobileRule(),
            ],
            'address' => [
                'sometimes',
            ],
            'postal_code' => [
                'sometimes',
                'numeric',
                'min:1',
                'max:15',
            ],
            'province' => [
                'sometimes',
                'exists:' . Province::class . ',id',
            ],
            'city' => [
                'required_with:province',
                new CityInProvinceRule(),
            ],
        ];
    }

    public function attributes()
    {
        return [
            'full_name' => 'نام گیرنده',
            'postal_code' => 'کد پستی',
        ];
    }
}
