<?php

namespace App\Repositories;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Models\Comment;
use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductCommentRepository extends Repository implements ProductCommentRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getCommentsSearchFilterPaginated(
        int     $productId,
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
                    ->withWhereHas('creator', function ($q) use ($search) {
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
                    ->orWhereLike([
                        'comments.pros',
                        'comments.cons',
                        'comments.description',
                    ], $search);
            })
            ->where('comments.product_id', $productId);

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }
}
