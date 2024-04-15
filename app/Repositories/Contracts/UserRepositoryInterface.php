<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Enums\Results\FavoriteProductResultEnum;
use App\Models\User;
use App\Support\Filter;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUsersSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param Filter|null $filter
     * @param array|null $reportQuery
     * @return Collection|LengthAwarePaginator
     */
    public function getUsersFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserAddressesSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserFavoriteProductsSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param array $columns
     * @param Filter|null $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserPurchasesSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator;

    /**
     * @param GetterExpressionInterface $where
     * @return Model|null
     */
    public function getUserAddressWhere(GetterExpressionInterface $where, array $columns = ['*']): ?Model;

    /**
     * @param User $user
     * @param Filter $filter
     * @param UserNotificationTypesEnum[] $notificationTypes
     * @param array $columns
     * @return Collection|LengthAwarePaginator
     */
    public function getUserNotifications(
        User  $user,
        Filter $filter,
        array $notificationTypes = [],
        array $columns = ['*']
    ): Collection|LengthAwarePaginator;

    /**
     * @param User $user
     * @param UserNotificationTypesEnum[] $notificationTypes
     * @param array $columns
     * @return Collection
     */
    public function getUnreadNotifications(
        User  $user,
        array $notificationTypes = [],
        array $columns = ['*']
    ): Collection;

    /**
     * @param GetterExpressionInterface $where
     * @return int
     */
    public function favoriteProductsCount(GetterExpressionInterface $where): int;

    /**
     * Toggle a product to user's favorite product
     *
     * @param $userId
     * @param $productId
     * @return FavoriteProductResultEnum
     */
    public function toggleFavoriteProduct($userId, $productId): FavoriteProductResultEnum;

    /**
     * @param GetterExpressionInterface $where
     * @return int
     */
    public function addressCount(GetterExpressionInterface $where): int;

    /**
     * @param array $data
     * @return mixed
     */
    public function createAddress(array $data): mixed;

    /**
     * @param User $user
     * @param UserNotificationTypesEnum[] $notificationTypes
     * @return bool
     */
    public function makeAllNotificationAsRead(User $user, array $notificationTypes = []): bool;

    /**
     * @param array $data
     * @param GetterExpressionInterface $where
     * @return int
     */
    public function updateUserAddressWhere(array $data, GetterExpressionInterface $where): int;

    /**
     * @param array $ids
     * @param bool $permanent
     * @return mixed
     */
    public function deleteBatch(array $ids, bool $permanent = false): mixed;

    /**
     * @param GetterExpressionInterface $where
     * @return mixed
     */
    public function deleteAddressWhere(GetterExpressionInterface $where): mixed;

    /**
     * @param GetterExpressionInterface $where
     * @return mixed
     */
    public function deleteFavoriteProductWhere(GetterExpressionInterface $where): mixed;
}
