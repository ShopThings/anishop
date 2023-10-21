<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeCategoryService extends Service implements ProductAttributeCategoryServiceInterface
{
    public function __construct(
        protected ProductAttributeCategoryRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCategories(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getAttributeCategoriesSearchFilterPaginated(
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'product_attribute_id' => $attributes['product_attribute'],
            'category_id' => $attributes['category'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['product_attribute'])) {
            $updateAttributes['product_attribute_id'] = $attributes['product_attribute'];
        }
        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
