<?php

namespace App\Services;

use App\Models\BlogComment;
use App\Repositories\BlogCommentRepository;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class BlogCommentService extends Service implements BlogCommentServiceInterface
{
    public function __construct(
        protected BlogCommentRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getComments(int $blogId, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getCommentsSearchFilterPaginated(blogId: $blogId, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function reportComment(BlogComment $comment): bool
    {
        $repository = new BlogCommentRepository($comment);
        return $repository->reportComment();
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'blog_id' => $attributes['blog'],
            'badge_id' => $attributes['badge'],
            'comment_id' => $attributes['comment'] ?? null,
            'answer_to' => $attributes['answer_to'] ?? null,
            'condition' => $attributes['condition'],
            'status' => $attributes['status'],
            'description' => $attributes['description'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['blog'])) {
            $updateAttributes['blog_id'] = $attributes['blog'];
        }
        if (isset($attributes['badge'])) {
            $updateAttributes['badge_id'] = $attributes['badge'];
        }
        if (isset($attributes['comment'])) {
            $updateAttributes['comment_id'] = $attributes['comment'];
        }
        if (isset($attributes['answer_to'])) {
            $updateAttributes['answer_to'] = $attributes['answer_to'];
        }
        if (isset($attributes['condition'])) {
            $updateAttributes['condition'] = $attributes['condition'];
        }
        if (isset($attributes['status'])) {
            $updateAttributes['status'] = $attributes['status'];
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
