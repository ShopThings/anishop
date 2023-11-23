<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Slider;
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
            'slides.*.slider' => [
                'required',
                'exists:' . Slider::class . ',id',
            ],
            'slides.*.priority' => [
                'required',
                'numeric',
                'min:0',
            ],
            'slides.*.options' => [
                'array',
            ],
            'slides.*.is_published' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'slides' => 'اسلاید',
            'slides.*.slider' => 'اسلایدر',
        ];
    }
}
