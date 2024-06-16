<?php

namespace App\Services;

use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    public function getAttributeCategories(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getAttributeCategoriesSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCategoriesCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @param int $productId
     * @return Collection|null
     */
    public function getProductAttributeCategories(int $productId): Collection|null
    {
        return $this->repository->getProductAttributeCategories($productId);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'product_attribute_id' => $attributes['attribute'],
            'category_id' => $attributes['category'],
            'priority' => $attributes['priority'] ?? 0,
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['attribute'])) {
            $updateAttributes['product_attribute_id'] = $attributes['attribute'];
        }
        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
