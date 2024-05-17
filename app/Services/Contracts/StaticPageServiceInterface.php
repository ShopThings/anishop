<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StaticPageServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getPages(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getPagesCount(): int;

    /**
     * @param array $urls
     * @param bool $permanent
     * @param bool $considerDeletable
     * @return bool
     */
    public function batchDeleteByUrls(
        array $urls,
        bool  $permanent = false,
        bool  $considerDeletable = false
    ): bool;
}
