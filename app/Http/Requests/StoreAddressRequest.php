<?php

namespace App\Http\Requests;

use App\Enums\DatabaseEnum;
use App\Models\Province;
use App\Rules\CityInProvinceRule;
use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'sometimes',
                'nullable',
                'regex:/[0-9]*/',
                'max:15',
            ],
            'province' => [
                'required',
                Rule::exists(Province::class, 'id')->where(function ($query) {
                    $query->where('is_published', DatabaseEnum::DB_YES);
                }),
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
            'postal_code' => 'کدپستی',
        ];
    }

    public function messages()
    {
        return [
            'postal_code.max' => 'کدپستی نباید بیشتر از ۱۵ رقم باشد.',
        ];
    }
}
