<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Repositories\FileRepository;
use App\Services\Contracts\FileServiceInterface;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FileRequest extends FormRequest
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
         * @var FileServiceInterface $service
         */
        $service = app()->make(FileServiceInterface::class);

        return [
            'disk' => [
                'sometimes',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, FileRepository::$storageDisks)) {
                        $fail('محل ذخیره‌سازی انتخاب شده نامعتبر می‌باشد.');
                    }
                },
            ],
            'path' => [
                'sometimes',
                'string',
                'nullable',
                function ($attribute, $value, $fail) use($service) {
                    if ($value && !$service->exists($value, $this->input('disk'))) {
                        $fail('مسیر فایل/پوشه نامعتبر می‌باشد.');
                    }
                },
            ],
            'destination' => [
                'sometimes',
                'string',
                function ($attribute, $value, $fail) use($service) {
                    if ($value && !$service->exists($value, $this->input('disk'))) {
                        $fail('مسیر فایل/پوشه مقصد نامعتبر می‌باشد.');
                    }
                },
            ],
        ];
    }
}
