<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface OrderBadgeServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getBadges(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getBadgesCount(): int;

    /**
     * @param string $code
     * @return Model|null
     */
    public function getBadgeByCode(string $code): ?Model;
}
