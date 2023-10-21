<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Rules\ColorRule;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOrderBadgeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::ORDER_BADGE
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
            'color_hex' => [
                'required',
                'max:12',
                new ColorRule(),
            ],
            'should_return_order_product' => [
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
            'color_hex' => 'کد رنگ',
            'should_return_order_product' => 'وضعیت بازگشت محصولات به انبار',
        ];
    }
}
