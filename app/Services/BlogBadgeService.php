<?php

namespace App\Services;

use App\Repositories\Contracts\BlogBadgeRepositoryInterface;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class BlogBadgeService extends Service implements BlogBadgeServiceInterface
{
    public function __construct(
        protected BlogBadgeRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBadges(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('blog_comment_badges');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
        });

        return $this->repository->paginate(
            where: $where->build(),
            limit: $filter->getLimit(),
            page: $filter->getPage(),
            order: $filter->getOrder()
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'color_hex' => $attributes['color_hex'],
            'is_published' => to_boolean($attributes['is_published']),
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['color_hex'])) {
            $updateAttributes['color_hex'] = $attributes['color_hex'];
        }
        if (isset($attributes['is_starting_badge'])) {
            $updateAttributes['is_starting_badge'] = to_boolean($attributes['is_starting_badge']);
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }
        if (isset($attributes['is_deletable'])) {
            $updateAttributes['is_deletable'] = to_boolean($attributes['is_deletable']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
