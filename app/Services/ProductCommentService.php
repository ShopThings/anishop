<?php

namespace App\Services;

use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;


class ProductCommentService extends Service implements ProductCommentServiceInterface
{
    public function __construct(
        protected ProductCommentRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getComments(int $productId, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getCommentsSearchFilterPaginated(
            productId: $productId,
            filter: $filter
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'product_id' => $attributes['product'],
            'condition' => $attributes['condition'],
            'status' => $attributes['status'],
            'pros' => $attributes['pros'],
            'cons' => $attributes['cons'],
            'description' => $attributes['description'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['product'])) {
            $updateAttributes['product_id'] = $attributes['product'];
        }
        if (isset($attributes['condition'])) {
            $updateAttributes['condition'] = $attributes['condition'];
        }
        if (isset($attributes['status'])) {
            $updateAttributes['status'] = $attributes['status'];
        }
        if (isset($attributes['pros'])) {
            $updateAttributes['pros'] = $attributes['pros'];
        }
        if (isset($attributes['cons'])) {
            $updateAttributes['cons'] = $attributes['cons'];
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
