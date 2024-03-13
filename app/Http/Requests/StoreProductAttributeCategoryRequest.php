<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductAttributeCategoryRequest extends FormRequest
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
            'attribute' => [
                'required',
                'exists:' . ProductAttribute::class . ',id',
            ],
            'category' => [
                'required',
                'exists:' . Category::class . ',id',
            ],
            'priority' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'attribute' => 'ویژگی محصول',
        ];
    }
}
