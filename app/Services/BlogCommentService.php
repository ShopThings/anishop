<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Models\BlogComment;
use App\Repositories\BlogCommentRepository;
use App\Repositories\Contracts\BlogBadgeRepositoryInterface;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogCommentService extends Service implements BlogCommentServiceInterface
{
    public function __construct(
        protected BlogCommentRepositoryInterface $repository,
        protected BlogBadgeRepositoryInterface   $blogBadgeRepository
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
    public function getUserComments($userId, Filter $filter): Collection|LengthAwarePaginator
    {
        $filter->setOrder(['id' => 'desc']);
        return $this->repository->getUserCommentsFilterPaginated(
            userId: $userId,
            filter: $filter,
            columns: ['id', 'condition', 'status', 'created_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserCommentsCount($userId): int
    {
        $where = new WhereBuilder('blog_comments');
        $where->whereEqual('created_by', $userId);

        return $this->repository->count($where->build());
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
        if (!isset($attributes['badge'])) {
            $where = new WhereBuilder();
            $where->whereEqual('is_stating_badge', DatabaseEnum::DB_YES);
            $attributes['badge'] = $this->blogBadgeRepository->findWhere($where->build(), ['id'])?->id;
        }

        $attrs = [
            'blog_id' => $attributes['blog'],
            'badge_id' => $attributes['badge'],
            'comment_id' => $attributes['comment'] ?? null,
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

    /**
     * @inheritDoc
     */
    public function deleteUserCommentById($userId, $id): bool
    {
        $where = new WhereBuilder('blog_comments');
        $where->whereEqual('created_by', $userId)
            ->whereEqual('id', $id);

        return (bool)$this->repository->deleteWhere($where->build(), true);
    }
}
