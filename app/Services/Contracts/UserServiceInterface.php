<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\User;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * @param $id
     * @return Model|null
     */
    public function getUserAddressById($id): ?Model;

    /**
     * @param $userId
     * @return int
     */
    public function getUserAddressesCount($userId): int;

    /**
     * @param $userId
     * @return int
     */
    public function getUserFavoriteProductsCount($userId): int;

    /**
     * @param User $user
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserNotifications(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param $userId
     * @param $productId
     * @return bool
     */
    public function addFavoriteProduct($userId, $productId): bool;

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function createAddress(array $attributes): ?Model;

    /**
     * @param User $user
     * @return bool
     */
    public function makeAllNotificationAsRead(User $user): bool;

    /**
     * @param $userId
     * @param $id
     * @param array $attributes
     * @return Model|null
     */
    public function updateUserAddressByUserIdAndId($userId, $id, array $attributes): ?Model;

    /**
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteAddressByUserIdAndId($userId, $id): bool;

    /**
     * @param $userId
     * @param $productId
     * @return bool
     */
    public function deleteUserFavoriteProductById($userId, $productId): bool;
}
