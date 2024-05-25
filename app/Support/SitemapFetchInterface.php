<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SitemapFetchInterface
{
    /**
     * [WARNING]: Please use this only in sitemap generation
     *
     * @param Filter $filter
     * @param array|null $condition
     * @return Collection|LengthAwarePaginator
     */
    public function getDataForSitemap(Filter $filter, ?array $condition = null): Collection|LengthAwarePaginator;
}
