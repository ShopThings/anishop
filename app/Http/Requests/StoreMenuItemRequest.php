<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMenuItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::MENU
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
            'menus' => [
                'required',
                'array',
                'min:1',
            ],
            'menus.*.menu' => [
                'required',
                'exists:' . Menu::class . ',id',
            ],
            'menus.*.parent' => [
                'required',
                'exists:' . MenuItem::class . ',id',
            ],
            'menus.*.title' => [
                'required',
                'max:250',
            ],
            'menus.*.link' => [
                'required',
                'url:http,https',
            ],
            'menus.*.is_published' => [
                'boolean',
            ],
            'menus.*.children' => [
                'sometimes',
                'array',
                'min:1',
            ],
            'menus.*.children.*.title' => [
                'sometimes',
                'max:250',
            ],
            'menus.*.children.*.link' => [
                'sometimes',
                'url:http,https',
            ],
            'menus.*.children.*.is_published' => [
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'menus' => 'منو',
            'menus.*.menu' => 'زیر منو',
            'menus.*.parent' => 'منوی والد',
            'menus.*.link' => 'لینک',
            'menus.*.children.*.menu' => 'زیر منو',
            'menus.*.children.*.parent' => 'زیر منوی والد',
            'menus.*.children.*.link' => 'لینک',
        ];
    }
}
