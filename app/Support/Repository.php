<?php

namespace App\Support;

use App\Contracts\RepositoryInterface;
use App\Support\Model\AuthenticatableExtendedModel;
use App\Support\Model\ExtendedModel;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var bool $useSoftDeletes
     */
    protected bool $useSoftDeletes = true;

    /**
     * @param ExtendedModel|AuthenticatableExtendedModel|Model $model
     */
    public function __construct(
        protected ExtendedModel|AuthenticatableExtendedModel|Model $model
    )
    {
    }

    /**
     * @param bool $res
     * @return static
     */
    protected function useSoftDeletes(bool $res = true): static
    {
        $this->useSoftDeletes = $res;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(?GetterExpressionInterface $where = null): bool
    {
        return $this->model->when($where, function (Builder $query) use ($where) {
            $query->whereRaw($where->getStatement(), $where->getBindings());
        })->exists();
    }

    /**
     * @inheritDoc
     */
    public function count(?GetterExpressionInterface $where = null): int
    {
        return $this->model->when($where, function (Builder $query) use ($where) {
            $query->whereRaw($where->getStatement(), $where->getBindings());
        })->count();
    }

    /**
     * @inheritDoc
     */
    public function all(
        array                      $columns = ['*'],
        ?GetterExpressionInterface $where = null,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): Collection
    {
        $query = $this->prepareGetQuery($where, $order, $withTrashed, $onlyTrashed);
        return $query->get($columns);
    }

    /**
     * @inheritDoc
     */
    public function chunk(
        callable                   $callback,
        ?GetterExpressionInterface $where = null,
        int                        $count = 200,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): bool
    {
        $query = $this->prepareGetQuery($where, $order, $withTrashed, $onlyTrashed);
        return $query->chunk($count, $callback);
    }

    /**
     * @inheritDoc
     */
    public function paginate(
        array                      $columns = ['*'],
        ?GetterExpressionInterface $where = null,
        int                        $limit = 15,
        int                        $page = 1,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): LengthAwarePaginator
    {
        $page = max($page, 1);
        $limit = $limit <= 0 ? null : $limit;
        $query = $this->prepareGetQuery($where, $order, $withTrashed, $onlyTrashed);
        return $query->paginate(perPage: $limit, columns: $columns, page: $page);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Builder|Model
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function find(
        $id,
        array $columns = ['*'],
        bool $withTrashed = false,
        bool $onlyTrashed = false
    ): Collection|Model|null
    {
        $query = $this->model->newQuery();

        if ($this->useSoftDeletes) {
            if ($onlyTrashed) {
                $query->onlyTrashed();
            } elseif ($withTrashed) {
                $query->withTrashed();
            }
        }

        return $query->find($id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(
        $id,
        array $columns = ['*'],
        bool $withTrashed = false,
        bool $onlyTrashed = false
    ): Collection|Model|null
    {
        $query = $this->model->newQuery();

        if ($this->useSoftDeletes) {
            if ($onlyTrashed) {
                $query->onlyTrashed();
            } elseif ($withTrashed) {
                $query->withTrashed();
            }
        }

        return $query->findOrFail($id, $columns);
    }


    public function findWhere(
        GetterExpressionInterface $where,
        array                     $columns = ['*'],
        bool                      $withTrashed = false,
        bool                      $onlyTrashed = false
    ): Model|null
    {
        $query = $this->prepareGetQuery($where, [], $withTrashed, $onlyTrashed);
        return $query->first($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data): int
    {
        return $this->model->newQuery()->findOrFail($id)->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateWhere(array $data, GetterExpressionInterface $where): int
    {
        return (bool)$this->model->when($where, function (Builder $query) use ($where) {
            $query->whereRaw($where->getStatement(), $where->getBindings());
        })->update($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id, bool $permanent = false): mixed
    {
        $model = $this->model->newQuery()->findOrFail($id);

        if ($permanent) {
            return $model->forceDelete();
        }
        return $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWhere(GetterExpressionInterface $where, bool $permanent = false): mixed
    {
        $query = $this->model->when($where, function (Builder $query) use ($where) {
            $query->whereRaw($where->getStatement(), $where->getBindings());
        });

        if ($permanent) {
            return $query->forceDelete();
        }
        return $query->delete();
    }

    /**
     * @param GetterExpressionInterface|null $where
     * @param array $order
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return Builder
     */
    protected function prepareGetQuery(
        ?GetterExpressionInterface $where = null,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): Builder
    {
        $query = $this->model->newQuery();

        if ($this->useSoftDeletes) {
            if ($onlyTrashed) {
                $query->onlyTrashed();
            } elseif ($withTrashed) {
                $query->withTrashed();
            }
        }

        $query->when($where, function (Builder $query) use ($where) {
            $query->whereRaw($where->getStatement(), $where->getBindings());
        });

        if (count($order)) {
            foreach ($order as $column => $sort) {
                $query->orderBy($column, $sort);
            }
        }

        return $query;
    }
}
