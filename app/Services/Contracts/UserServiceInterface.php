<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface extends ServiceInterface
{
    /**
     * @param WhereBuilderInterface|null $where
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getUsers(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param $id
     * @param bool $permanent
     * @return bool
     */
    public function deleteUser($id, bool $permanent = false): bool;

    /**
     * @param array $ids
     * @param bool $permanent
     * @return bool
     */
    public function batchDelete(array $ids, bool $permanent = false): bool;
}
