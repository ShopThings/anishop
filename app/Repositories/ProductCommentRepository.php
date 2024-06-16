<?php

namespace App\Repositories;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Enums\Comments\CommentVotingTypesEnum;
use App\Http\Requests\Filters\HomeCommentFilter;
use App\Models\Comment;
use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        ?int $productId = null,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->with([
                'product',
                'product.image',
                'answeredBy',
                'conditionChanger',
                'creator',
                'updater',
                'deleter',
            ])
            ->when($search, function (Builder $query, string $search) use ($filter) {
                $query
                    ->when($filter->getRelationSearch(), function ($q) use ($search) {
                        $q->orWhereHas('creator', function ($q) use ($search) {
                            $q->where(function ($q) use ($search) {
                                $q->orWhereLike([
                                    'username',
                                    'first_name',
                                    'last_name',
                                    'national_code',
                                ], $search);
                            });
                        });
                    })
                    ->when(CommentConditionsEnum::getSimilarValuesFromString($search), function (Builder $builder, array $conditions) {
                        $builder->orWhereIn('condition', $conditions);
                    })
                    ->when(CommentStatusesEnum::getSimilarValuesFromString($search), function (Builder $builder, array $statuses) {
                        $builder->orWhereIn('status', $statuses);
                    })
                    ->orWhereLike([
                        'comments.pros',
                        'comments.cons',
                        'comments.description',
                    ], $search);
            })
            ->when($productId, function ($q, $productId) {
                $q->where('comments.product_id', $productId);
            });

        if ($filter instanceof HomeCommentFilter) {
            $query->accepted();
        }

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserCommentsFilterPaginated(
        int    $userId,
        Filter $filter,
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query
            ->with(['product', 'product.image'])
            ->where('created_by', $userId);

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function reportComment(): bool
    {
        return !!$this->model->increment('flag_count', 1);
    }

    /**
     * @inheritDoc
     */
    public function voteComment(CommentVotingTypesEnum $type): bool
    {
        $res = match ($type) {
            CommentVotingTypesEnum::LIKING => $this->model->increment('up_vote_count', 1),
            CommentVotingTypesEnum::UNDO_LIKING => $this->model->decrement('up_vote_count', 1),
            CommentVotingTypesEnum::DISLIKING => $this->model->increment('down_vote_count', 1),
            CommentVotingTypesEnum::UNDO_DISLIKING => $this->model->decrement('down_vote_count', 1),
            default => null,
        };

        if (!is_null($res)) return $res;

        // some cases have multiple instructions and didn't fit in 'match' so... yeah(goes below)

        $res = false;
        $res2 = false;

        DB::beginTransaction();

        if (CommentVotingTypesEnum::FROM_LIKE_TO_DISLIKING === $type) {
            $res = $this->model->decrement('up_vote_count', 1);
            $res2 = $this->model->increment('down_vote_count', 1);
        } elseif (CommentVotingTypesEnum::FROM_DISLIKING_TO_LIKE === $type) {
            $res2 = $this->model->decrement('down_vote_count', 1);
            $res = $this->model->increment('up_vote_count', 1);
        }

        if ($res === false || $res2 === false) DB::rollBack();
        else DB::commit();

        return !!$res && !!$res2;
    }
}
