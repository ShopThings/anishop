<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Repository;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUsersSearchFilterPaginated(
        array   $columns = ['*'],
        ?string $search = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = []
    ): Collection|LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query->when($search, function (Builder $query, string $search) {
            $query
                ->when(RolesEnum::getSimilarRoleValuesFromString($search), function (Builder $builder, array $roles) {
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

        if (count($order)) {
            foreach ($order as $column => $sort) {
                $query->orderBy($column, $sort);
            }
        }

        if($limit > 0) {
            return $query->paginate(perPage: $limit, columns: $columns, page: $page);
        } else {
            return $query->get($columns);
        }
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
