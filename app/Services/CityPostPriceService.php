<?php

namespace App\Services;

use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    public function getPostPrices(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getPostPricesSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getPostPriceByCityId(int $cityId): ?Model
    {
        $where = new WhereBuilder();
        $where->whereEqual('city_id', $cityId);

        return $this->repository->findWhere($where->build());
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
