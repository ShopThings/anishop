<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\User;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUsers(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserAddresses(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserFavoriteProduct(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserPurchases(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCarts(User $user);
}
