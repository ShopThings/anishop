<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Results\FavoriteProductResultEnum;
use App\Models\AddressUser;
use App\Models\User;
use App\Models\UserFavoriteProduct;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Filter;
use App\Support\QB\ReportQueryAppenderTrait;
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
    use RepositoryTrait,
        ReportQueryAppenderTrait;

    /**
     * @inheritDoc
     */
    protected function getMappedReportColumnToActualColumn(): array
    {
        return [
            'username' => 'username',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'national_code' => 'national_code',
            'sheba_number' => 'sheba_number',
            'is_admin' => 'is_admin',
            'is_banned' => 'is_banned',
            'is_verified' => 'verified_at',
            'is_deleted' => 'deleted_at',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getSpecialReportColumns(): array
    {
        return ['is_verified', 'is_deleted'];
    }

    /**
     * @inheritDoc
     */
    protected function getSpecialBooleanColumns(): array
    {
        return ['is_verified', 'is_deleted'];
    }

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
        $query
            ->with(['creator', 'updater', 'roles'])
            ->when($search, function (Builder $query, string $search) {
                $query
                    ->when(RolesEnum::getSimilarValuesFromString($search), function (Builder $builder, array $roles) {
                        $builder->whereHas('roles', function ($q) use ($roles) {
                            $q->whereIn('name', $roles);
                        });
                    })
                    ->orWhereLike([
                        'users.username',
                        'users.first_name',
                        'users.last_name',
                        'users.national_code',
                        'users.sheba_number'
                    ], $search);
            });

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUsersFilterPaginatedForReport(
        Filter $filter = null,
        ?array $reportQuery = null
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $query = $this->model->newQuery();
        $query->with(['creator', 'updater', 'roles']);

        if (!empty($reportQuery)) {
            $query = $this->addToEloquentBuilder($query, $reportQuery);
        }

        return $this->_paginateWithOrder(query: $query, limit: $limit, page: $page, order: $order);
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
                    ->orWhereHas('city', function ($q) use ($search) {
                        $q->where('name', $search);
                    })
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('name', $search);
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
                'product',
                'product.image:full_path'
            ])
            ->when($search, function (Builder $query, string $search) {
                $query->orWhereHas('product', function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
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
        $query->when($search, function (Builder $query, string $search) use ($filter) {
            $query
                ->when($filter->getRelationSearch(), function ($q) use ($search) {
                    $q->orWhereHas('orders', function ($q) use ($search) {
                        $q->where(function ($q) use ($search) {
                            $q
                                ->when(PaymentStatusesEnum::getSimilarValuesFromString($search), function (Builder $q2, array $statuses) {
                                    $q2->orWhereIn('payment_status', $statuses);
                                })
                                ->orWhereHas('items', function ($q2) use ($search) {
                                    $q2->where(function ($q) use ($search) {
                                        $q->orWhereLike([
                                            'order_items.product_title',
                                            'order_items.color_name',
                                            'order_items.size',
                                            'order_items.guarantee',
                                        ], $search);
                                    });
                                })
                                ->orWhereLike([
                                    'orders.payment_method_title',
                                ], $search);
                        });
                    });
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

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUserAddressWhere(GetterExpressionInterface $where, array $columns = ['*']): ?Model
    {
        return $this->addressUserModel->newQuery()
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
            ->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function getUserNotifications(
        User   $user,
        Filter $filter,
        array $notificationTypes = [],
        array  $columns = ['*']
    ): Collection|LengthAwarePaginator
    {
        $limit = $filter->getLimit();
        $page = $filter->getPage();
        $order = $filter->getOrder();

        $types = [];
        foreach ($notificationTypes as $notificationType) {
            if ($notificationType instanceof UserNotificationTypesEnum) {
                $types[] = $notificationType;
            }
        }

        $query = $user->notifications();
        $query
            ->when(!empty($types), function ($q) use ($types) {
                $q->whereIn('data->type', array_map(fn($item) => $item->value, $types));
            })
            ->orderByRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(data, '$.priority')) AS UNSIGNED) DESC")
            ->orderByDesc('created_at')
            ->orderByRaw('read_at IS NULL')
            ->orderByDesc('read_at');

        return $this->_paginateWithOrder($query, $columns, $limit, $page, $order);
    }

    /**
     * @inheritDoc
     */
    public function getUnreadNotifications(
        User  $user,
        array $notificationTypes = [],
        array $columns = ['*']
    ): Collection
    {
        $types = [];
        foreach ($notificationTypes as $notificationType) {
            if ($notificationType instanceof UserNotificationTypesEnum) {
                $types[] = $notificationType;
            }
        }

        $query = $user->unreadNotifications();
        $query
            ->when(!empty($types), function ($q) use ($types) {
                $q->whereIn('data->type', array_map(fn($item) => $item->value, $types));
            })
            ->orderByRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(data, '$.priority')) AS UNSIGNED) DESC")
            ->orderByDesc('created_at');

        return $query->get($columns);
    }

    /**
     * @inheritDoc
     */
    public function favoriteProductsCount(GetterExpressionInterface $where): int
    {
        return $this->userFavoriteProductModel->newQuery()
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function toggleFavoriteProduct($userId, $productId): FavoriteProductResultEnum
    {
        $query = $this->userFavoriteProductModel->newQuery()
            ->where('user_id', $userId)
            ->where('product_id', $productId);

        if ($query->exists()) {
            $query->delete();
            return FavoriteProductResultEnum::REMOVED;
        } else {
            $res = $this->userFavoriteProductModel->create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }

        return $res instanceof Model
            ? FavoriteProductResultEnum::REMOVED
            : FavoriteProductResultEnum::ERROR;
    }

    /**
     * @inheritDoc
     */
    public function addressCount(GetterExpressionInterface $where): int
    {
        return $this->addressUserModel->newQuery()
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
            ->count();
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
    public function makeAllNotificationAsRead(User $user, array $notificationTypes = []): bool
    {
        $types = [];
        foreach ($notificationTypes as $notificationType) {
            if ($notificationType instanceof UserNotificationTypesEnum) {
                $types[] = $notificationType;
            }
        }

        $query = $user->notifications()
            ->whereNull('read_at')
            ->when(!empty($types), function ($q) use ($types) {
                $q->whereIn('data->type', array_map(fn($item) => $item->value, $types));
            });

        if ($query->exists()) {
            return (bool)$query->update([
                'read_at' => now(),
            ]);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function updateUserAddressWhere(array $data, GetterExpressionInterface $where): int
    {
        return $this->addressUserModel->newQuery()
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
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
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
            ->forceDelete();
    }

    /**
     * @inheritDoc
     */
    public function deleteFavoriteProductWhere(GetterExpressionInterface $where): mixed
    {
        return $this->userFavoriteProductModel->newQuery()
            ->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            })
            ->forceDelete();
    }
}
