<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\ProductAttributeValue;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductAttributeValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE
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
            'product_attribute' => [
                'required',
                'exists:' . ProductAttributeValue::class . ',id',
            ],
            'attribute_value' => [
                'required',
                'max:250',
            ],
            'priority' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'product_attribute' => 'ویژگی محصول',
            'attribute_value' => 'مثدار ویژگی',
        ];
    }
}
