<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Category;
use App\Models\FileManager;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::CATEGORY
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
            'parent' => [
                'required',
                'exists:' . Category::class . ',id',
            ],
            'name' => [
                'required',
                'max:250',
            ],
            'image' => [
                'required',
                'exists:' . FileManager::class . ',id',
            ],
            'level' => [
                'required',
                'numeric',
            ],
            'priority' => [
                'required',
                'numeric',
            ],
            'show_in_menu' => [
                'required',
                'boolean',
            ],
            'show_in_search_side_menu' => [
                'required',
                'boolean',
            ],
            'show_in_slider' => [
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
            'show_in_menu' => 'وضعیت نمایش در منوی اصلی',
            'show_in_search_side_menu' => 'وضعیت نمایش در منوی کنار صفحه جستجوی محصولات',
            'show_in_slider' => 'وضعیت نمایش در اسلایدر',
        ];
    }
}
