<?php

namespace App\Http\Requests;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
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
        return [
            'blog' => [
                'required',
                'exists:' . Blog::class . ',id',
            ],
            'comment' => [
                'sometimes',
                'exists:' . BlogComment::class . ',id',
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'blog' => 'بلاگ برای ثبت دیدگاه',
            'comment' => 'دیدگاه مورد پاسخ',
        ];
    }
}
