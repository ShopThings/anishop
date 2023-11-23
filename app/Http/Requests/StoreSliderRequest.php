<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\SliderPlace;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSliderRequest extends FormRequest
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
            'slider_place' => [
                'required',
                'exists:' . SliderPlace::class . ',id',
            ],
            'title' => [
                'required',
                'max:250',
            ],
            'priority' => [
                'required',
                'numeric',
                'min:0',
            ],
            'options' => [
                'array',
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
            'slider_place' => 'محل نمایش',
            'options' => 'اطلاعات اضافی',
        ];
    }
}
