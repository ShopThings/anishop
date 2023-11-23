<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Services\Contracts\ProvinceServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Support\Collection;

class ProvinceService extends Service implements ProvinceServiceInterface
{
    public function __construct(
        protected ProvinceRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProvinces(): Collection
    {
        $where = new WhereBuilder('provinces');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository->all(where: $where->build());
    }
}
