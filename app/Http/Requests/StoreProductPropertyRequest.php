<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Color;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductPropertyRequest extends FormRequest
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
            'products' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.color' => [
                'required',
                'nullable',
                'exists:' . Color::class . ',id',
            ],
            'products.*.size' => [
                'required',
                'nullable',
                'max:250',
            ],
            'products.*.guarantee' => [
                'required',
                'nullable',
            ],
            'products.*.weight' => [
                'required',
                'numeric',
            ],
            'products.*.price' => [
                'required',
                'numeric',
            ],
            'products.*.discounted_price' => [
                'required',
                'nullable',
                'numeric',
                'lt:products.*.price'
            ],
            'products.*.discounted_from' => [
                'required',
                'nullable',
                'date-format:Y-m-d H:i',
                'before:end_at',
            ],
            'products.*.discounted_until' => [
                'required',
                'nullable',
                'date-format:Y-m-d H:i',
            ],
            'products.*.tax_rate' => [
                'required',
                'numeric',
                'min:0',
                'max: 100',
            ],
            'products.*.stock_count' => [
                'required',
                'numeric',
                'min:1',
            ],
            'products.*.max_cart_count' => [
                'required',
                'numeric',
                'min:1',
                'lte:products.*.stock_count',
            ],
            'products.*.is_special' => [
                'required',
                'boolean',
            ],
            'products.*.is_available' => [
                'required',
                'boolean',
            ],
            'products.*.show_coming_soon' => [
                'required',
                'boolean',
            ],
            'products.*.show_call_for_more' => [
                'required',
                'boolean',
            ],
            'products.*.is_published' => [
                'required',
                'boolean',
            ],
            'products.*.has_separate_shipment' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'products' => 'محصول/محصولات',
            'products.*.weight' => 'وزن با بسته‌بندی',
            'products.*.discounted_price' => 'قیمت با تخفیف',
            'products.*.discounted_from' => 'تخفیف از تاریخ',
            'products.*.discounted_until' => 'تخفیف تا تاریخ',
            'products.*.tax_rate' => 'مالیات بر ارزش افزوده',
            'products.*.stock_count' => 'تعداد کالا در انبار',
            'products.*.max_cart_count' => 'تعداد کالا در سبد خرید',
            'products.*.is_special' => 'ویژه بودن محصول',
            'products.*.show_coming_soon' => 'نمایش بزودی',
            'products.*.show_call_for_more' => 'نمایش تماس برای اطلاعات بیشتر',
            'products.*.has_separate_shipment' => 'مرسوله مجزا',
        ];
    }
}
