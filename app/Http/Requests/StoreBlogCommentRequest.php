<?php

namespace App\Http\Requests;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCommentBadge;
use App\Models\User;
use App\Support\Gate\PermissionHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreBlogCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()
            ?->can(PermissionHelper::permission(
                PermissionsEnum::CREATE,
                PermissionPlacesEnum::BLOG_COMMENT
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
            'blog' => [
                'required',
                'exists:' . Blog::class . ',id',
            ],
            'badge' => [
                'required',
                'exists:' . BlogCommentBadge::class . ',id',
            ],
            'comment' => [
                'required',
                'exists:' . BlogComment::class . ',id',
            ],
            'answer_to' => [
                'required',
                'exists:' . User::class . ',id',
            ],
            'description' => [
                'required',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'blog' => 'بلاگ برای ثبت نظر',
            'badge' => 'برچسب نظر',
            'comment' => 'نظر مورد پاسخ',
            'answer_to' => 'پاسخ گیرنده',
        ];
    }
}
