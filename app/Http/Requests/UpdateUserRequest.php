<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\User;
use App\Rules\PersianNameRule;
use App\Rules\PersianNationalCodeRule;
use App\Services\Contracts\RoleServiceInterface;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::USER));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        /**
         * @var RoleServiceInterface $service
         */
        $service = app(RoleServiceInterface::class);

        $roles = $service->getRoles();
        $roles = array_map(function ($value) {
            return $value['value'];
        }, $roles);

        return [
            'password' => [
                'sometimes',
                (new Password(9))->numbers()->letters(),
                'confirmed',
            ],
            'roles' => [
                'sometimes',
                'min:1',
                function ($attribute, $value, $fail) use ($roles) {
                    if (!is_array($value)) $value = [$value];

                    $arr = array_map(function ($v) {
                        return $v['value'] ?? $v;
                    }, $value);
                    $uniqueArr = array_unique($arr);

                    if (count($uniqueArr) != count($arr)) {
                        $fail('نقش باید دارای موارد یکتا باشد.');
                    } else {
                        foreach ($uniqueArr as $item) {
                            if (!in_array($item, $roles)) {
                                $fail('نقش انتخاب شده نامعتبر می‌باشد.');
                                break;
                            }
                        }
                    }
                },
            ],
            'first_name' => [
                'sometimes',
                new PersianNameRule(),
            ],
            'last_name' => [
                'sometimes',
                new PersianNameRule(),
            ],
            'national_code' => [
                'sometimes',
                new PersianNationalCodeRule(),
            ],
            'shaba_number' => [
                'sometimes',
                'nullable',
                'max:30'
            ],
            'is_banned' => [
                'sometimes',
                'boolean',
            ],
            'ban_desc' => [
                'required_if:ban_status,1',
                'nullable',
            ],
            'is_deletable' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
