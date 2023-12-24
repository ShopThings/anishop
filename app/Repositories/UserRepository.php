<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Models\AddressUser;
use App\Models\User;
use App\Models\UserFavoriteProduct;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository extends Repository implements UserRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(
        User                          $model,
        protected AddressUser         $addressUserModel,
        protected UserFavoriteProduct $userFavoriteProductModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUsersSearchFilterPaginated(
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->when(RolesEnum::getSimilarValuesFromString($search), function (Builder $builder, array $roles) {
                    $builder->withWhereHas('roles', function ($q) use ($roles) {
                        $q->whereIn('name', $roles);
                    });
                })
                ->orWhereLike([
                    'users.username',
                    'users.first_name',
                    'users.last_name',
                    'users.national_code',
                    'users.shaba_number'
                ], $search);
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserAddressesSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $user->addresses();
        $query
            ->with(['province', 'city'])
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->where(function (Builder $builder) use ($search) {
                        $builder
                            ->whereHas('city', function ($q) use ($search) {
                                $q->where('name', $search);
                            })
                            ->whereHas('province', function ($q) use ($search) {
                                $q->where('name', $search);
                            });
                    })
                    ->orWhereLike([
                        'address_user.full_name',
                        'address_user.mobile',
                        'address_user.address',
                        'address_user.postal_code',
                    ], $search);
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserFavoriteProductsSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $user->favoriteProducts();
        $query
            ->with([
                'product:id,title,slug',
                'product.image:full_path'
            ])
            ->when($search, function (Builder $query, string $search) {
                $query->where(function (Builder $builder) use ($search) {
                    $builder->whereHas('product', function ($q) use ($search) {
                        $q->orWhereLike([
                            'title',
                            'escaped_title',
                        ], $search);
                    });
                });
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserPurchasesSearchFilterPaginated(
        User   $user,
        array  $columns = ['*'],
        Filter $filter = null
    ): Collection|LengthAwarePaginator
    {
        $search = $filter->getSearchText();
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $user->orders();
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->where(function (Builder $builder) use ($search) {
                    $builder
                        ->withWhereHas('orders', function ($q) use ($search) {
                            $q
                                ->when(PaymentStatusesEnum::getSimilarValuesFromString($search), function (Builder $q2, array $statuses) {
                                    $q2->orWhereIn('payment_status', $statuses);
                                })
                                ->withWhereHas('items', function ($q2) use ($search) {
                                    $q2->orWhereLike([
                                        'order_items.product_title',
                                        'order_items.color_name',
                                        'order_items.size',
                                        'order_items.guarantee',
                                    ], $search);
                                })
                                ->orWhereLike('orders.payment_method_title', $search);
                        })
                        ->orWhereLike([
                            'order_details.first_name',
                            'order_details.last_name',
                            'order_details.mobile',
                            'order_details.province',
                            'order_details.city',
                            'order_details.address',
                            'order_details.postal_code',
                            'order_details.receiver_name',
                            'order_details.receiver_mobile',
                            'order_details.send_status_title',
                        ], $search);
                });
        });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserAddressWhere(GetterExpressionInterface $where, array $columns = ['*']): ?Model
    {
        return $this->addressUserModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function getUserNotifications(
        User   $user,
        Filter $filter,
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $user->notifications();

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function addFavoriteProduct($userId, $productId): bool
    {
        $res = $this->userFavoriteProductModel->firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        if ($res instanceof Model) return true;
        return false;
    }

    /**
     * @inheritDoc
     */
    public function createAddress(array $data): Builder|Model
    {
        return $this->addressUserModel->create($data);
    }

    /**
     * @inheritDoc
     */
    public function makeAllNotificationAsRead(User $user): int
    {
        return $user->notifications()->whereNull('read_at')->update([
            'read_at' => now(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function updateUserAddressWhere(array $data, GetterExpressionInterface $where): int
    {
        return $this->addressUserModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->update($data);
    }

    /**
     * @inheritDoc
     */
    public function deleteBatch(array $ids, bool $permanent = false): mixed
    {
        $where = new WhereBuilder('users');
        $where->whereIn('id', $ids)->whereEqual('is_deletable', DatabaseEnum::DB_YES);
        return $this->deleteWhere($where->build(), $permanent);
    }

    /**
     * @inheritDoc
     */
    public function deleteAddressWhere(GetterExpressionInterface $where): mixed
    {
        return $this->addressUserModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->forceDelete();
    }

    /**
     * @inheritDoc
     */
    public function deleteFavoriteProductWhere(GetterExpressionInterface $where): mixed
    {
        return $this->userFavoriteProductModel->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings())
            ->forceDelete();
    }
}
