<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::BLOG_CATEGORY
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
            'name' => [
                'required',
                'max:250',
            ],
            'priority' => [
                'required',
                'numeric',
                'min:0',
            ],
            'keywords' => [
                'array',
            ],
            'is_published' => [
                'required',
                'boolean',
            ],
            'show_in_menu' => [
                'required',
                'boolean',
            ],
            'show_in_side_menu' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'show_in_menu' => 'نمایش در منوی اصلی',
            'show_in_side_menu' => 'نمایش در منوی کناری',
        ];
    }
}
