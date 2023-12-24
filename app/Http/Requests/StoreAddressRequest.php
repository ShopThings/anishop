<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Province;
use App\Rules\CityInProvince;
use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
                'required',
                new PersianNameRule(),
                'max:150',
            ],
            'mobile' => [
                'required',
                new PersianMobileRule(),
            ],
            'address' => [
                'required',
            ],
            'postal_code' => [
                'required',
                'numeric',
                'min:1',
                'max:15',
            ],
            'province' => [
                'required',
                'exists:' . Province::class . ',id',
            ],
            'city' => [
                'required_with:province',
                new CityInProvince(),
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
