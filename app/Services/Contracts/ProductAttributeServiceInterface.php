<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductAttributeServiceInterface extends ServiceInterface
{
    /**
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getAttributes(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator;
}
