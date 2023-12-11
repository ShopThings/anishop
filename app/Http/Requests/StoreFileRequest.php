<?php

namespace App\Http\Requests;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Services\Contracts\FileServiceInterface;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::USER
            ));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'mimes:jpeg,png,jpg,gif,svg,csv,txt,xlx,xlsx,xls,pdf,docx,doc,mp4,mp3',
                'max:512000'
            ],
            'disk' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, FileRepositoryInterface::STORAGE_DISKS)) {
                        $fail('محل ذخیره‌سازی انتخاب شده نامعتبر می‌باشد.');
                    }
                },
            ],
            'path' => [
                'required',
                'string',
                'nullable',
                function ($attribute, $value, $fail) {
                    /**
                     * @var FileServiceInterface $service
                     */
                    $service = app(FileServiceInterface::class);
                    if ($value && !$service->exists($value, $this->input('disk'))) {
                        $fail('مسیر فایل/پوشه نامعتبر می‌باشد.');
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'file' => 'فایل',
            'disk' => 'محل ذخیره‌سازی',
            'path' => 'مسیر فایل/پوشه',
        ];
    }
}
