<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FileManager;
use App\Models\Unit;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::PRODUCT
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
            'brand' => [
                'required',
                'exists:' . Brand::class . ',id',
            ],
            'category' => [
                'required',
                'exists:' . Category::class . ',id',
            ],
            'title' => [
                'required',
                'max:250',
            ],
            'image' => [
                'required',
                'exists:' . FileManager::class . ',id',
            ],
            'quick_properties' => [
                'required',
                'array',
            ],
            'unit' => [
                'required',
                'exists:' . Unit::class . ',id',
            ],
            'keywords' => [
                'array',
            ],
            'is_available' => [
                'required',
                'boolean',
            ],
            'is_commenting_allowed' => [
                'required',
                'boolean',
            ],
            'is_published' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'quick_properties' => 'ویژگی‌های سریع',
            'unit' => 'واحد محصول',
            'is_commenting_allowed' => 'اجازه ارسال دیدگاه',
        ];
    }
}
