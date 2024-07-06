<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Results\FavoriteProductResultEnum;
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
     * @param bool|null $isAdmin
     * @return int
     */
    public function getUsersCount(?bool $isAdmin = null): int;

    /**
     * @param string $username
     * @return Model|null
     */
    public function getUserByUsername(string $username): ?Model;

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
    public function getAdminUserNotifications(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @return int
     */
    public function getAdminUnreadNotificationsCount(User $user): int;

    /**
     * @param User $user
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserNotifications(User $user, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @return int
     */
    public function getUnreadNotificationsCount(User $user): int;

    /**
     * @param $userId
     * @param $productId
     * @return FavoriteProductResultEnum
     */
    public function toggleFavoriteProduct($userId, $productId): FavoriteProductResultEnum;

    /**
     * @param $userId
     * @return bool
     */
    public function canCreateAddress($userId): bool;

    /**
     * @param string $username
     * @param string|null $password
     * @return Model|null
     */
    public function createTemporaryUser(string $username, ?string $password = null): ?Model;

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function createAddress(array $attributes): ?Model;

    /**
     * @param User $user
     * @return bool
     */
    public function makeAllAdminNotificationsAsRead(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function makeAllNotificationsAsRead(User $user): bool;

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
