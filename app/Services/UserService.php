<?php

namespace App\Services;

use App\Enums\Gates\RolesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Enums\Results\FavoriteProductResultEnum;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends Service implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected CartRepositoryInterface $cartRepository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsers(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getUsersSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getUserByUsername(string $username): ?Model
    {
        if (empty($username)) return null;

        $where = new WhereBuilder('users');
        $where->whereEqual('username', $username);

        return $this->repository->findWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserAddresses(User $user, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserAddressesSearchFilterPaginated(user: $user, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getUserFavoriteProduct(User $user, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserFavoriteProductsSearchFilterPaginated(user: $user, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getUserPurchases(User $user, Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserPurchasesSearchFilterPaginated(user: $user, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getUserAddressById($id): ?Model
    {
        $where = new WhereBuilder('address_user');
        $where->whereEqual('id', $id);

        return $this->repository->getUserAddressWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserAddressesCount($userId): int
    {
        $where = new WhereBuilder('address_user');
        $where->whereEqual('user_id', $userId);

        return $this->repository->addressCount($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserFavoriteProductsCount($userId): int
    {
        $where = new WhereBuilder('user_favorite_products');
        $where->whereEqual('user_id', $userId);

        return $this->repository->favoriteProductsCount($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getAdminUserNotifications(User $user, Filter $filter): Collection|LengthAwarePaginator
    {
        $filter->setOrder(['created_at' => 'desc']);
        return $this->repository->getUserNotifications(
            user: $user,
            filter: $filter,
            notificationTypes: [UserNotificationTypesEnum::getAdminTypes()],
            columns: ['data', 'read_at', 'created_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function getAdminUnreadNotifications(User $user): Collection
    {
        return $this->repository->getUnreadNotifications(
            user: $user,
            notificationTypes: [UserNotificationTypesEnum::getAdminTypes()],
            columns: ['data', 'read_at', 'created_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserNotifications(User $user, Filter $filter): Collection|LengthAwarePaginator
    {
        $filter->setOrder(['created_at' => 'desc']);
        return $this->repository->getUserNotifications(
            user: $user,
            filter: $filter,
            notificationTypes: [UserNotificationTypesEnum::getUserTypes()],
            columns: ['data', 'read_at', 'created_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function getUnreadNotifications(User $user): Collection
    {
        return $this->repository->getUnreadNotifications(
            user: $user,
            notificationTypes: [UserNotificationTypesEnum::getUserTypes()],
            columns: ['data', 'read_at', 'created_at']
        );
    }

    /**
     * @inheritDoc
     */
    public function toggleFavoriteProduct($userId, $productId): FavoriteProductResultEnum
    {
        return $this->repository->toggleFavoriteProduct($userId, $productId);
    }

    /**
     * @inheritDoc
     */
    public function canCreateAddress($userId): bool
    {
        $where = new WhereBuilder('address_user');
        $where->whereEqual('user_id', $userId);

        $max = config('market.user.max_address_count', 0);
        return $this->repository->addressCount($where->build()) >= $max;
    }

    /**
     * @inheritDoc
     */
    public function createTemporaryUser(string $username, ?string $password = null): ?Model
    {
        if (empty($password)) {
            $password = Str::random(12);
        }

        $attrs = [
            'username' => $username,
            'password' => Hash::make($password),
        ];
        $role = [RolesEnum::USER->value];

        DB::beginTransaction();

        $user = $this->repository->create($attrs);
        $user->syncRoles($role);

        if ($user instanceof Model) {
            DB::commit();
            return $user;
        }
        DB::rollBack();
        return null;
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $isAdmin = false;

        if (isset($attributes['roles']) && is_array($attributes['roles'])) {
            $roles = array_map(function ($value) {
                if (isset($value['value'])) {
                    return $value['value'];
                } elseif ($value instanceof RolesEnum) {
                    return $value->value;
                }
                return null;
            }, $attributes['roles']);

            $roles = array_filter($roles, fn($item) => !is_null($item));
            $roles = array_unique($roles);

            foreach ($roles as $role) {
                if (RolesEnum::isAdminRole($role)) {
                    $isAdmin = true;
                    break;
                }
            }
        } else {
            $roles = [RolesEnum::USER->value];
        }

        $attrs = [
            'username' => $attributes['username'],
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'national_code' => $attributes['national_code'],
            'sheba_number' => $attributes['sheba_number'] ?? null,
            'password' => Hash::make($attributes['password']),
            'is_admin' => $isAdmin,
            'verified_at' => now(),
        ];

        //-----
        DB::beginTransaction();

        $user = $this->repository->create($attrs);
        $user->syncRoles($roles);

        if ($user instanceof Model) {
            DB::commit();
            return $user;
        }
        DB::rollBack();
        return null;
    }

    /**
     * @inheritDoc
     */
    public function createAddress(array $attributes): ?Model
    {
        $attrs = [
            'user_id' => $attributes['user_id'],
            'full_name' => $attributes['full_name'],
            'mobile' => $attributes['mobile'],
            'address' => $attributes['address'],
            'postal_code' => $attributes['postal_code'],
            'province_id' => $attributes['province'],
            'city_id' => $attributes['city'],
        ];

        return $this->repository->createAddress($attrs);
    }

    /**
     * @inheritDoc
     */
    public function makeAllAdminNotificationAsRead(User $user): bool
    {
        return $this->repository->makeAllNotificationAsRead(
            $user,
            UserNotificationTypesEnum::getAdminTypes()
        );
    }

    /**
     * @inheritDoc
     */
    public function makeAllNotificationAsRead(User $user): bool
    {
        return $this->repository->makeAllNotificationAsRead(
            $user,
            UserNotificationTypesEnum::getUserTypes()
        );
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['username'])) {
            $updateAttributes['username'] = $attributes['username'];
        }
        if (isset($attributes['first_name'])) {
            $updateAttributes['first_name'] = $attributes['first_name'];
        }
        if (isset($attributes['last_name'])) {
            $updateAttributes['last_name'] = $attributes['last_name'];
        }
        if (isset($attributes['national_code'])) {
            $updateAttributes['national_code'] = $attributes['national_code'];
        }
        if (isset($attributes['sheba_number'])) {
            $updateAttributes['sheba_number'] = $attributes['sheba_number'];
        }
        if (isset($attributes['password'])) {
            $updateAttributes['password'] = Hash::make($attributes['password']);
        }
        if (isset($attributes['is_banned'])) {
            $isBanned = to_boolean($attributes['is_banned']);
            $updateAttributes['is_banned'] = $isBanned;

            if ($isBanned && isset($attributes['ban_desc'])) {
                $updateAttributes['ban_desc'] = $attributes['ban_desc'];
            } else {
                $updateAttributes['ban_desc'] = null;
            }
        }
        if (isset($attributes['is_deletable'])) {
            $updateAttributes['is_deletable'] = to_boolean($attributes['is_deletable']);
        }

        if (isset($attributes['roles'])) {
            $roles = array_map(function ($value) {
                return $value['value'];
            }, $attributes['roles']);

            $isAdmin = false;
            foreach ($roles as $role) {
                if (RolesEnum::isAdminRole($role)) {
                    $isAdmin = true;
                    break;
                }
            }

            $updateAttributes['is_admin'] = $isAdmin;
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        $user = $this->getById($id);

        if (isset($attributes['roles'])) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function updateUserAddressByUserIdAndId($userId, $id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['full_name'])) {
            $updateAttributes['full_name'] = $attributes['full_name'];
        }
        if (isset($attributes['mobile'])) {
            $updateAttributes['mobile'] = $attributes['mobile'];
        }
        if (isset($attributes['address'])) {
            $updateAttributes['address'] = $attributes['address'];
        }
        if (isset($attributes['postal_code'])) {
            $updateAttributes['postal_code'] = $attributes['postal_code'];
        }
        if (isset($attributes['province'])) {
            $updateAttributes['province_id'] = $attributes['province'];
        }
        if (isset($attributes['city'])) {
            $updateAttributes['city_id'] = $attributes['city'];
        }

        $where = new WhereBuilder('address_user');
        $where->whereEqual('id', $id)
            ->whereEqual('user_id', $userId);

        $res = $this->repository->updateUserAddressWhere($updateAttributes, $where->build());

        return !$res ? null : $this->getUserAddressById($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteAddressByUserIdAndId($userId, $id): bool
    {
        $where = new WhereBuilder('address_user');
        $where->whereEqual('id', $id)
            ->whereEqual('user_id', $userId);

        return (bool)$this->repository->deleteAddressWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function deleteUserFavoriteProductById($userId, $productId): bool
    {
        $where = new WhereBuilder('user_favorite_products');
        $where
            ->whereEqual('user_id', $userId)
            ->whereEqual('product_id', $productId);

        return (bool)$this->repository->deleteFavoriteProductWhere($where->build());
    }
}
