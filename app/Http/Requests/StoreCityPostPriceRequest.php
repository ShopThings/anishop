<?php

namespace App\Http\Requests;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Province;
use App\Rules\CityInProvinceRule;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCityPostPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::CITY_POST_PRICE
            ));
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
                'required',
                Rule::exists(Province::class, 'id')->where(function ($query) {
                    $query->where('is_published', DatabaseEnum::DB_YES);
                }),
            ],
            'city' => [
                'required_with:province',
                new CityInProvinceRule(),
            ],
            'post_price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'post_price' => 'هزینه ارسال',
        ];
    }
}
