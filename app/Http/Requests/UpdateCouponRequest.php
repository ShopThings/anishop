<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
                'sometimes',
                'max:250',
            ],
            'code' => [
                'sometimes',
                'unique:' . Coupon::class . ',code',
            ],
            'price' => [
                'sometimes',
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
                'sometimes',
                'numeric',
                'min:1',
            ],
            'reusable_after' => [
                'sometimes',
                'numeric',
                'min:1',
            ],
            'is_published' => [
                'sometimes',
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
