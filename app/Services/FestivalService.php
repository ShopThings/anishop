<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\FestivalServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use App\Traits\DatabaseDateTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FestivalService extends Service implements FestivalServiceInterface
{
    use DatabaseDateTrait;

    public function __construct(
        protected FestivalRepositoryInterface $repository,
        protected ProductRepositoryInterface  $productRepository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getFestivals(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder();
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query
                ->when(
                    $this->getValidDatabaseDate($search),
                    function (WhereBuilderInterface $q, $date) {
                        $q
                            ->orWhereGreaterThanEqual('start_at', $date)
                            ->orWhereLessThanEqual('end_at', $date);
                    })
                ->orWhereLike('title', $search);
        });

        return $this->repository
            ->newWith(['creator', 'updater', 'deleter'])
            ->paginate(
                where: $where->build(),
                limit: $filter->getLimit(),
                page: $filter->getPage(),
                order: $filter->getOrder()
            );
    }

    /**
     * @inheritDoc
     */
    public function getFestivalsCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getHomeFestivals(): Collection
    {
        $where = new WhereBuilder();
        $where
            ->whereEqual('is_published', DatabaseEnum::DB_YES)
            ->group(function (WhereBuilderInterface $where) {
                $where
                    ->orWhereNotNull('start_at')
                    ->orWhereNotNull('end_at');
            })
            ->group(function (WhereBuilderInterface $where) {
                $where
                    ->orGroup(function (WhereBuilderInterface $builder) {
                        $builder
                            ->whereNotNull('start_at')
                            ->whereLessThanEqual('start_at', now());
                    })
                    ->orGroup(function ($builder) {
                        $builder
                            ->whereNotNull('start_at')
                            ->whereGreaterThanEqual('end_at', now());
                    });
            });

        return $this->repository->all([
            'id', 'title', 'slug',
            'start_at', 'end_at',
        ], $where->build(), ['created_at' => 'asc']);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'start_at' => isset($attributes['start_at']) && !empty($attributes['start_at'])
                ? $this->getValidDatabaseDate($attributes['start_at'])
                : null,
            'end_at' => isset($attributes['end_at']) && !empty($attributes['end_at'])
                ? $this->getValidDatabaseDate($attributes['end_at'])
                : null,
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

        if (isset($attributes['start_at'])) {
            if (!empty($attributes['start_at'])) {
                $updateAttributes['start_at'] = $this->getValidDatabaseDate($attributes['start_at']);
            } else {
                $updateAttributes['start_at'] = null;
            }
        }

        if (isset($attributes['end_at'])) {
            if (!empty($attributes['end_at'])) {
                $updateAttributes['end_at'] = $this->getValidDatabaseDate($attributes['end_at']);
            } else {
                $updateAttributes['end_at'] = null;
            }
        }

        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getFestivalProducts(
        int    $festivalId,
        Filter $filter
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getFestivalProductsSearchFilterPaginated(
            festivalId: $festivalId,
            filter: $filter
        );
    }

    /**
     * @inheritDoc
     */
    public function addProductToFestival($productId, $festivalId, $discountPercentage): ?Model
    {
        return $this->repository->addProductToFestival($productId, $festivalId, $discountPercentage);
    }

    /**
     * @inheritDoc
     */
    public function removeProductFromFestival($productId, $festivalId): bool
    {
        return $this->repository->removeProductFromFestival($productId, $festivalId);
    }

    /**
     * @inheritDoc
     */
    public function removeProductsFromFestival($festivalId, array $ids): bool
    {
        return $this->repository->removeProductsFromFestival($festivalId, $ids);
    }

    /**
     * @inheritDoc
     */
    public function addCategoryToFestival($categoryId, $festivalId, $discountPercentage): Collection
    {
        $productIds = $this->_getCategoryProductsIds($categoryId);
        $products = collect();

        foreach ($productIds as $id) {
            $tmp = $this->addProductToFestival($id, $festivalId, $discountPercentage);

            if (!is_null($tmp))
                $products->add($tmp);
        }

        return $products;
    }

    /**
     * @inheritDoc
     */
    public function removeCategoryFromFestival($categoryId, $festivalId): bool
    {
        $productIds = $this->_getCategoryProductsIds($categoryId);
        return $this->removeProductsFromFestival($festivalId, $productIds);
    }

    /**
     * @param $categoryId
     * @return array
     */
    protected function _getCategoryProductsIds($categoryId): array
    {
        $where = new WhereBuilder('products');
        $where->whereEqual('category_id', $categoryId);

        $products = $this->productRepository->all(
            columns: ['id'],
            where: $where->build()
        );

        return $products->pluck('id')->toArray();
    }
}
