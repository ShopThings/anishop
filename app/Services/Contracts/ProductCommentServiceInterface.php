<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Comments\CommentVotingTypesEnum;
use App\Models\Comment;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductCommentServiceInterface extends ServiceInterface
{
    /**
     * @param int $productId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getComments(int $productId, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getCommentsCount(): int;

    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getAllComments(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param $userId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserComments($userId, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getNotSeenCommentsCount(): int;

    /**
     * @param $userId
     * @return int
     */
    public function getUserCommentsCount($userId): int;

    /**
     * @param Comment $comment
     * @return bool
     */
    public function reportComment(Comment $comment): bool;

    /**
     * @param Comment $comment
     * @param CommentVotingTypesEnum $type
     * @return bool
     */
    public function voteComment(Comment $comment, CommentVotingTypesEnum $type): bool;

    /**
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteUserCommentById($userId, $id): bool;
}
