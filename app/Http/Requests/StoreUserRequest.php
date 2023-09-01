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

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::USER));
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
            'username' => [
                'required',
                'unique:' . User::class . ',username',
            ],
            'password' => [
                'required',
                (new Password(9))->numbers()->letters(),
                'confirmed',
            ],
            'roles' => [
                'required',
                'min:1',
                function ($attribute, $value, $fail) use($roles) {
                    if(!is_array($value)) $value = [$value];

                    $arr = array_map(function ($v) {
                        return $v['value'] ?? $v;
                    }, $value);
                    $uniqueArr = array_unique($arr);

                    if (count($uniqueArr) != count($arr)) {
                        $fail('نقش باید دارای موارد یکتا باشد.');
                    } else {
                        foreach ($uniqueArr as $item) {
                            if(!in_array($item, $roles)) {
                                $fail('نقش انتخاب شده نامعتبر می‌باشد.');
                                break;
                            }
                        }
                    }
                },
            ],
            'first_name' => [
                'required',
                new PersianNameRule(),
            ],
            'last_name' => [
                'required',
                new PersianNameRule(),
            ],
            'national_code' => [
                'required',
                new PersianNationalCodeRule(),
            ],
            'shaba_number' => [
                'nullable',
                'max:30'
            ],
        ];
    }
}
