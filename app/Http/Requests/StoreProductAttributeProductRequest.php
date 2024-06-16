<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductAttributeProductRequest extends FormRequest
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
            'values' => [
                'sometimes',
                'array',
            ],
            'values.*.id' => [
                'sometimes',
                'exists:' . ProductAttributeValue::class . ',id',
            ],
            'values.*.product_attribute_id' => [
                'sometimes',
                'exists:' . ProductAttribute::class . ',id',
            ],
            'values.*.attribute_value' => [
                'sometimes',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'values' => 'مقادیر ویژگی‌ها',
            'values.*.id' => 'شناسه مقدار ویژگی',
        ];
    }
}
