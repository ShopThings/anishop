<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Services\Contracts\CityServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Support\Collection;

class CityService extends Service implements CityServiceInterface
{
    public function __construct(
        protected CityRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCities(int $provinceId): Collection
    {
        $where = new WhereBuilder('cities');
        $where
            ->whereEqual('province_id', $provinceId)
            ->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository->all(where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function isCityInProvince(int $cityId, int $provinceId): bool
    {
        $where = new WhereBuilder();
        $where
            ->whereEqual('id', $cityId)
            ->whereEqual('province_id', $provinceId)
            ->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository->exists(where: $where->build());
    }
}
