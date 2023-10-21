<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function App\Support\Helper\to_boolean;

class UserService extends Service implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected CartRepositoryInterface $cartRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsers(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getUsersSearchFilterPaginated(
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserAddresses(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserAddressesSearchFilterPaginated(
            user: $user,
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserFavoriteProduct(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserFavoriteProductsSearchFilterPaginated(
            user: $user,
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserPurchases(
        User    $user,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['id' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getUserPurchasesSearchFilterPaginated(
            user: $user,
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserCarts(User $user)
    {
        return $this->cartRepository->getUserCarts($user);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
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

        $attrs = [
            'username' => $attributes['username'],
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'national_code' => $attributes['national_code'],
            'shaba_number' => $attributes['shaba_number'] ?? null,
            'password' => Hash::make($attributes['password']),
            'is_admin' => $isAdmin,
            'verified_at' => now(),
        ];

        //-----
        DB::beginTransaction();

        $user = $this->repository->create($attrs);
        $user->assignRole($roles);

        if ($user instanceof Model) {
            DB::commit();
            return $user;
        } else {
            DB::rollBack();
            return null;
        }
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
        if (isset($attributes['shaba_number'])) {
            $updateAttributes['shaba_number'] = $attributes['shaba_number'];
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
}
