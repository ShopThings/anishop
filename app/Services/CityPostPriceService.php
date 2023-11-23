<?php

namespace App\Services;

use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class CityPostPriceService extends Service implements CityPostPriceServiceInterface
{
    public function __construct(
        protected CityPostPriceRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getPostPrices(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getPostPricesSearchFilterPaginated(
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
            'city_id' => $attributes['city'],
            'post_price' => $attributes['post_price'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['city'])) {
            $updateAttributes['city_id'] = $attributes['city'];
        }
        if (isset($attributes['post_price'])) {
            $updateAttributes['post_price'] = $attributes['post_price'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
