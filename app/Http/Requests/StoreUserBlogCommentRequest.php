<?php

namespace App\Http\Requests;

use App\Models\BlogComment;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserBlogCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $model = $this->route('blog');

        return [
            'comment' => [
                'sometimes',
                'exists:' . BlogComment::class . ',id',
                function ($attribute, $value, $fail) use ($model) {
                    if ($value && is_null($model->comments()->where('id', $value)->first())) {
                        $fail('دیدگاه انتخاب شده جهت پاسخ، نامعتبر می‌باشد.');
                    }
                },
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'comment' => 'دیدگاه مورد پاسخ',
        ];
    }
}
