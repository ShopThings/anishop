<?php

namespace App\Services;

use App\Repositories\Contracts\StaticPageRepositoryInterface;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StaticPageService extends Service implements StaticPageServiceInterface
{
    public function __construct(
        protected StaticPageRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getPages(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('static_pages');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'title',
                'keywords',
            ], $search);
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
    public function getPagesCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'url' => $attributes['url'],
            'keywords' => $attributes['keywords'],
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
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes[' description'];
        }
        if (isset($attributes['url'])) {
            $updateAttributes['url'] = $attributes['url'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
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

    /**
     * @inheritDoc
     */
    public function batchDeleteByUrls(
        array $urls,
        bool  $permanent = false,
        bool  $considerDeletable = false
    ): bool
    {
        $where = new WhereBuilder();
        $where->whereIn('url', $urls);
        $ids = $this->repository->all(columns: ['id'], where: $where->build())->pluck('id');
        return $this->batchDeleteByIds($ids->toArray(), $permanent, $considerDeletable);
    }
}
