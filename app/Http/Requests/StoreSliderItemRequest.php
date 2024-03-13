<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Blog;
use App\Models\Product;
use App\Rules\FileExistsRule;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSliderItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::SLIDER
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
            'slides' => [
                'required',
                'array',
                'min:1',
            ],
            // instead of using 'slides.*.image.full_path', this way is more reliable
            'slides.*.image' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (!$value['full_path']) {
                        $fail('لطفا تصاویر خود را دوباره بررسی نمایید.');
                        return;
                    }
                    (new FileExistsRule())->validate($attribute, $value['full_path'], $fail);
                },
            ],
            'slides.*.link' => [
                'sometimes',
                'url:http,https',
            ],
            'slides.*.product_id' => [
                'sometimes',
                'exists:' . Product::class . ',id',
            ],
            'slides.*.blog_id' => [
                'sometimes',
                'exists:' . Blog::class . ',id',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'slides' => 'اسلاید',
        ];
    }
}
