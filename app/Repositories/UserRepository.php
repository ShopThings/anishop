<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Filter;
use App\Support\Repository;
use App\Support\Traits\RepositoryTrait;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserRepository extends Repository implements UserRepositoryInterface
{
    use RepositoryTrait;

    public function __construct(User $model)
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
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->where(function (Builder $builder) use ($search) {
                    $builder
                        ->withWhereHas('city', function ($q) use ($search) {
                            $q->where('name', $search);
                        })
                        ->withWhereHas('province', function ($q) use ($search) {
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
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->where(function (Builder $builder) use ($search) {
                    $builder
                        ->withWhereHas('product', function ($q) use ($search) {
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
    public function deleteBatch(array $ids, bool $permanent = false): mixed
    {
        $where = new WhereBuilder('users');
        $where->whereIn('id', $ids)->whereEqual('is_deletable', DatabaseEnum::DB_YES);
        return $this->deleteWhere($where->build(), $permanent);
    }
}
