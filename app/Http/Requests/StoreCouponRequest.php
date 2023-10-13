<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Coupon;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::COUPON
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
            'code' => [
                'required',
                'unique:' . Coupon::class . ',code',
            ],
            'price' => [
                'required',
                'numeric',
            ],
            'apply_min_price' => [
                'sometimes',
                'numeric',
            ],
            'apply_max_price' => [
                'sometimes',
                'numeric',
            ],
            'start_at' => [
                'sometimes',
                'date-format:YYYY-MM-DD HH:mm',
                'before:end_at',
            ],
            'end_at' => [
                'sometimes',
                'date-format:YYYY-MM-DD HH:mm',
                'after:start_at',
            ],
            'use_count' => [
                'required',
                'numeric',
                'min:1',
            ],
            'reusable_after' => [
                'required',
                'numeric',
                'min:1',
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
            'apply_min_price' => 'حداقل قیمت اعمال کوپن',
            'apply_max_price' => 'حداکثر قیمت اعمال کوپن',
            'start_at' => 'تاریخ شروع',
            'end_at' => 'تاریخ انقضا',
            'use_count' => 'تعداد قابل استفاده',
            'reusable_after' => 'قابل استفاده پس از(بر حسب روز)',
        ];
    }
}
