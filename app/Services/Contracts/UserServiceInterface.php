<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @param bool $permanent
     * @return bool
     */
    public function delete($id, bool $permanent = false): bool;

    /**
     * @param array $ids
     * @param bool $permanent
     * @return bool
     */
    public function batchDelete(array $ids, bool $permanent = false): bool;
}
