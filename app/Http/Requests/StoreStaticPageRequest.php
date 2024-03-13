<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStaticPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::STATIC_PAGE
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
                'required',
            ],
            'url' => [
                'required',
                'regex:/[a-z]+[a-z\/\-][a-z]+/i',
                Rule::unique('static_pages', 'url'),
            ],
            'keywords' => [
                'array',
            ],
            'is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'url' => 'آدرس صفحه',
        ];
    }
}
