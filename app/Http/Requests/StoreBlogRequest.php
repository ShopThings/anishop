<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\BlogCategory;
use App\Models\FileManager;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::BLOG
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
            'category' => [
                'required',
                'exists:' . BlogCategory::class . ',id',
            ],
            'title' => [
                'required',
                'max:250',
            ],
            'image' => [
                'required',
                'exists:' . FileManager::class . ',id',
            ],
            'description' => [
                'required',
            ],
            'keywords' => [
                'array',
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
            'is_commenting_allowed' => 'اجازه ارسال نظر',
        ];
    }
}
