<?php

namespace App\Services;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Comments\CommentVotingTypesEnum;
use App\Models\Comment;
use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Repositories\ProductCommentRepository;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class ProductCommentService extends Service implements ProductCommentServiceInterface
{
    public function __construct(
        protected ProductCommentRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getComments(int $productId, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getCommentsSearchFilterPaginated(
            productId: $productId,
            filter: $filter
        );
    }

    /**
     * @inheritDoc
     */
    public function getCommentsCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getAllComments(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getCommentsSearchFilterPaginated(filter: $filter);
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
        );
    }

    /**
     * @inheritDoc
     */
    public function getNotSeenCommentsCount(): int
    {
        $where = new WhereBuilder('comments');
        $where->whereEqual('status', CommentStatusesEnum::UNREAD->value);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserCommentsCount($userId): int
    {
        $where = new WhereBuilder('comments');
        $where->whereEqual('created_by', $userId);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function reportComment(Comment $comment): bool
    {
        $repository = new ProductCommentRepository($comment);
        return $repository->reportComment();
    }

    /**
     * @inheritDoc
     */
    public function voteComment(Comment $comment, CommentVotingTypesEnum $type): bool
    {
        $repository = new ProductCommentRepository($comment);
        return $repository->voteComment($type);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'product_id' => $attributes['product'],
            'condition' => $attributes['condition'] ?? CommentConditionsEnum::UNSET,
            'status' => $attributes['status'] ?? CommentStatusesEnum::UNREAD,
            'pros' => $attributes['pros'] ?? [],
            'cons' => $attributes['cons'] ?? [],
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

        if (isset($attributes['product'])) {
            $updateAttributes['product_id'] = $attributes['product'];
        }
        if (isset($attributes['condition'])) {
            $updateAttributes['condition'] = $attributes['condition'];
            $updateAttributes['changed_condition_at'] = now();
            $updateAttributes['changed_condition_by'] = Auth::user()?->id;
        }
        if (isset($attributes['status'])) {
            $updateAttributes['status'] = $attributes['status'];
        }
        if (isset($attributes['pros'])) {
            $updateAttributes['pros'] = $attributes['pros'];
        }
        if (isset($attributes['cons'])) {
            $updateAttributes['cons'] = $attributes['cons'];
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }
        if (isset($attributes['answer'])) {
            $updateAttributes['answer'] = $attributes['answer'];
            $updateAttributes['answered_at'] = now();
            $updateAttributes['answered_by'] = Auth::user()?->id;
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
        $where = new WhereBuilder('comments');
        $where->whereEqual('id', $id)
            ->whereEqual('created_by', $userId);

        return (bool)$this->repository->deleteWhere($where->build(), true);
    }
}
