<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\User;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserServiceInterface extends ServiceInterface
{
    /**
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
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
     * @param User $user
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getUserAddresses(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getUserFavoriteProduct(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param string|null $searchText
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return Collection|LengthAwarePaginator
     */
    public function getUserPurchases(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCarts(User $user);

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function create(array $attributes): ?Model;

    /**
     * @param $id
     * @param array $attributes
     * @return Model|null
     */
    public function updateById($id, array $attributes): ?Model;
}
