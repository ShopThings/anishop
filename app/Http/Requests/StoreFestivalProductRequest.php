<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Category;
use App\Models\Product;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFestivalProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::FESTIVAL
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
                'sometimes',
                'exists:' . Category::class . ',id',
            ],
            'product' => [
                'sometimes',
                'exists:' . Product::class . ',id',
            ],
            'discount_percentage' => [
                'required',
                'min:1',
                'max:100',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'discount_percentage' => 'درصد تخفیف',
        ];
    }
}
