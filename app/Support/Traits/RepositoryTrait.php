<?php

namespace App\Support\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

trait RepositoryTrait
{
    /**
     * @param $query
     * @param array $columns
     * @param int|null $limit
     * @param int $page
     * @param array $order
     * @return LengthAwarePaginator|Collection
     */
    protected function _paginateWithOrder(
        $query,
        array $columns = ['*'],
        ?int $limit = 15,
        int $page = 1,
        array $order = []
    )
    {
        $this->prepareWith($query);

        if (count($order)) {
            foreach ($order as $column => $sort) {
                $query->orderBy($column, $sort);
            }
        }

        if ($limit && $limit > 0) {
            return $query->paginate(perPage: $limit, columns: $columns, page: $page);
        } else {
            return $query->get($columns);
        }
    }
}
