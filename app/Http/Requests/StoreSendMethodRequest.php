<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Rules\FileExistsRule;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSendMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::SEND_METHOD
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
            'title' => [
                'required',
                'max:250',
            ],
            'description' => [
                'sometimes',
            ],
            'image' => [
                'required',
                new FileExistsRule(),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'priority' => [
                'required',
                'numeric',
                'min:0',
            ],
            'determine_price_by_shop_location' => [
                'boolean',
            ],
            'only_for_shop_location' => [
                'boolean',
            ],
            'apply_number_of_shipments_on_price' => [
                'boolean',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'price' => 'هزینه ارسال',
            'determine_price_by_shop_location' => 'در نظرگیری مکان فروشگاه برای قیمت ارسال',
            'only_for_shop_location' => 'اعمال فقط برای محدوده مکان فروشگاه',
            'apply_number_of_shipments_on_price' => 'اعمال هزینه ارسال به ازای هر مرسوله',
        ];
    }
}
