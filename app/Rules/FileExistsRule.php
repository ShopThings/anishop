<?php

namespace App\Rules;

use App\Repositories\Contracts\FileRepositoryInterface;
use App\Services\Contracts\FileServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class FileExistsRule implements DataAwareRule, ValidationRule
{
    /**
     * All data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $message = 'فایل انتخاب شده نامعتبر است.';

        $info = pathinfo($value);
        if (empty($info['extension'])) {
            $fail($message);
        }

        /**
         * @var FileServiceInterface $service
         */
        $service = app()->get(FileServiceInterface::class);

        $disk = $this->data['disk'] ?? null;
        if (empty($disk) || !in_array($disk, FileRepositoryInterface::STORAGE_DISKS)) {
            $disk = FileRepositoryInterface::STORAGE_DISK_PUBLIC;
        }

        if (!$service->exists($value, $disk)) {
            $fail($message);
        }
    }

    /**
     * Set the data under validation.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
