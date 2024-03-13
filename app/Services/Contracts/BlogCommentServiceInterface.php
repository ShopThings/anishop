<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\BlogComment;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogCommentServiceInterface extends ServiceInterface
{
    /**
     * @param int $blogId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getComments(int $blogId, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param $userId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserComments($userId, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @inheritDoc
     */
    public function getUserCommentsCount($userId): int;

    /**
     * @param BlogComment $comment
     * @return bool
     */
    public function reportComment(BlogComment $comment): bool;

    /**
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteUserCommentById($userId, $id): bool;
}
