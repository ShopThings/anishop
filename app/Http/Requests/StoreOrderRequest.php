<?php

namespace App\Http\Requests;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\PaymentMethod;
use App\Models\Province;
use App\Models\SendMethod;
use App\Rules\CityInProvinceRule;
use App\Rules\PersianMobileRule;
use App\Rules\PersianNameRule;
use App\Rules\PersianNationalCodeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart_name' => [
                'required',
                'string',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            //
            'first_name' => [
                'required',
                'string',
                new PersianNameRule(),
            ],
            'last_name' => [
                'required',
                'string',
                new PersianNameRule(),
            ],
            'national_code' => [
                'required',
                'numeric',
                new PersianNationalCodeRule(),
            ],
            'receiver_name' => [
                'required',
                'string',
                new PersianNameRule(),
            ],
            'receiver_mobile' => [
                'required',
                'numeric',
                new PersianMobileRule(),
            ],
            'postal_code' => [
                'sometimes',
                'nullable',
                'regex:/[0-9]{10}/',
            ],
            'address' => [
                'required',
                'string',
            ],
            //
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
            'send_method' => [
                'required',
                Rule::exists(SendMethod::class, 'id')->where(function ($query) {
                    $query->where('is_published', DatabaseEnum::DB_YES);
                }),
            ],
            'payment_method' => [
                'required',
                Rule::exists(PaymentMethod::class, 'id')->where(function ($query) {
                    $query->where('is_published', DatabaseEnum::DB_YES);

                    if (
                        !Auth::user()?->hasRole(RolesEnum::DEVELOPER->value) ||
                        app()->isProduction()
                    ) {
                        $query->where('is_sealed', DatabaseEnum::DB_NO);
                    }
                }),
            ],
            'coupon' => [
                'sometimes',
                'nullable',
                'string',
            ],
            //
            'is_needed_factor' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'cart_name' => 'نام سبد خرید',
            'items' => 'محصولات انتخاب شده',
            'first_name' => 'نام خریدار',
            'last_name' => 'نام خانوادگی خریدار',
            'national_code' => 'کد ملی خریدار',
            'receiver_name' => 'نام گیرنده',
            'receiver_mobile' => 'شماره تماس گیرنده',
            'postal_code' => 'کدپستی',
            'address' => 'آدرس',
            'province' => 'استان',
            'city' => 'شهر',
            'send_method' => 'روش ارسال',
            'payment_method' => 'روش پرداخت',
            'coupon' => 'کد کوپن',
            'is_needed_factor' => 'درخواست فاکتور',
        ];
    }

    public function messages()
    {
        return [
            'postal_code.max' => 'کدپستی باید برابر ۱۰ رقم باشد.',
        ];
    }
}
