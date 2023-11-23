<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWeightPostPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::WEIGHT_POST_PRICE
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
            'min_weight' => [
                'required',
                'numeric',
                'min:0',
                'lt:max_weight',
            ],
            'max_weight' => [
                'required',
                'numeric',
                'min:0',
                'gt:min_weight',
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
            'min_weight' => 'حداقل وزن مرسولات',
            'max_weight' => 'حداکثر وزن مرسولات',
            'post_price' => 'هزینه ارسال',
        ];
    }
}
