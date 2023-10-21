<?php

namespace App\Repositories;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Models\BlogComment;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BlogCommentRepository extends Repository implements BlogCommentRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(BlogComment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCommentsSearchFilterPaginated(
        int     $blogId,
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->withWhereHas('blog', function ($q) use ($search) {
                        $q->orWhereLike([
                            'escaped_title',
                            'keywords',
                        ], $search);
                    })
                    ->withWhereHas('badge', function ($q) use ($search) {
                        $q->orWhereLike('title', $search);
                    })
                    ->withWhereHas('creator', function ($q) use ($search) {
                        $q->orWhereLike([
                            'username',
                            'first_name',
                            'last_name',
                            'national_code',
                        ], $search);
                    })
                    ->withWhereHas('answerTo', function ($q) use ($search) {
                        $q->orWhereLike([
                            'username',
                            'first_name',
                            'last_name',
                            'national_code',
                        ], $search);
                    })
                    ->when(CommentConditionsEnum::getSimilarValuesFromString($search), function (Builder $builder, array $conditions) {
                        $builder->whereIn('condition', $conditions);
                    })
                    ->when(CommentStatusesEnum::getSimilarValuesFromString($search), function (Builder $builder, array $statuses) {
                        $builder->whereIn('status', $statuses);
                    })
                    ->orWhereLike('blog_comments.description', $search);
            })
            ->where('blog_comments.blog_id', $blogId);

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
