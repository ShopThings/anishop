<?php

namespace App\Support;

use App\Contracts\RepositoryInterface;
use App\Support\Model\AuthenticatableExtendedModel;
use App\Support\Model\ExtendedModel;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var array
     */
    private array $with = [];

    /**
     * @var array
     */
    private array $withWhereHas = [];

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
    public function resetWith(): static
    {
        $this->with = [];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resetWithWhereHas(): static
    {
        $this->withWhereHas = [];
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function newWith(array|string $relations, Closure|null|string $callback = null): static
    {
        $this->resetWith();
        return $this->with($relations, $callback);
    }

    /**
     * @inheritDoc
     */
    public function with(array|string $relations, Closure|null|string $callback = null): static
    {
        if (is_array($relations)) {
            foreach ($relations as $relation) {
                $this->with[] = [
                    'relations' => $relation,
                    'callback' => $callback,
                ];
            }
        } elseif (trim($relations) !== '') {
            $this->with[] = compact('relations', 'callback');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function newWithWhereHas(array|string $relations, Closure|null|string $callback = null): static
    {
        $this->resetWithWhereHas();
        return $this->withWhereHas($relations, $callback);
    }

    /**
     * @inheritDoc
     */
    public function withWhereHas(array|string $relations, Closure|null|string $callback = null): static
    {
        if (is_array($relations)) {
            foreach ($relations as $relation) {
                $this->withWhereHas[] = [
                    'relations' => $relation,
                    'callback' => $callback,
                ];
            }
        } elseif (trim($relations) !== '') {
            $this->withWhereHas[] = compact('relations', 'callback');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(?GetterExpressionInterface $where = null): bool
    {
        return $this->model->when(
            !is_null($where) && !empty($where->getStatement()),
            function (Builder $query) use ($where) {
                $query->whereRaw($where->getStatement(), $where->getBindings());
            }
        )->exists();
    }

    /**
     * @inheritDoc
     */
    public function count(
        ?GetterExpressionInterface $where = null,
        bool                       $withTrashed = false
    ): int
    {
        $query = $this->model->when(
            !is_null($where) && !empty($where->getStatement()),
            function (Builder $query) use ($where) {
                $query->whereRaw($where->getStatement(), $where->getBindings());
            }
        );

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->count();
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
        ?int                       $limit = 15,
        int                        $page = 1,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): LengthAwarePaginator
    {
        $page = max($page, 1);
        $limit = !$limit || $limit <= 0 ? null : $limit;
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
        array $order = [],
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

        $query = $this->applyOrderToQuery($query, $order);

        return $query->find($id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(
        $id,
        array $columns = ['*'],
        array $order = [],
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

        $query = $this->applyOrderToQuery($query, $order);

        return $query->findOrFail($id, $columns);
    }


    public function findWhere(
        GetterExpressionInterface $where,
        array                     $columns = ['*'],
        array $order = [],
        bool                      $withTrashed = false,
        bool                      $onlyTrashed = false
    ): Model|null
    {
        $query = $this->prepareGetQuery($where, [], $withTrashed, $onlyTrashed);
        $query = $this->applyOrderToQuery($query, $order);

        return $query->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreate(array $data, array $values = []): Builder|Model
    {
        return $this->model->newQuery()->updateOrCreate($data, $values);
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data): int
    {
        return $this->model->newQuery()->findOrFail($id)->update($data);
    }

    /**
     * @inheritDoc
     */
    public function updateWhere(array $data, GetterExpressionInterface $where): int
    {
        if (is_null($where) || empty($where->getStatement())) return 0;

        $model = $this->findWhere($where);
        if (!$model instanceof Model) return 0;
        return $this->update($model->id, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete($id, bool $permanent = false): mixed
    {
        $model = $this->model->newQuery()->findOrFail($id);

        if ($permanent) {
            if ($model instanceof EloquentCollection) {
                foreach ($model as $item) {
                    $item->forceDelete();
                }
            } else {
                $model->forceDelete();
            }

            return true;
        }

        if ($model instanceof EloquentCollection) {
            foreach ($model as $item) {
                $item->delete();
            }
        } else {
            $model->delete();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteWhere(GetterExpressionInterface $where, bool $permanent = false): mixed
    {
        $query = $this->model->newQuery()
            ->whereRaw($where->getStatement(), $where->getBindings());

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

        $this->prepareWith($query);
        $query->when(
            !is_null($where) && !empty($where->getStatement()),
            function (Builder $query) use ($where) {
                $query->whereRaw($where->getStatement(), $where->getBindings());
            }
        );

        $query = $this->applyOrderToQuery($query, $order);

        return $query;
    }

    /**
     * @param $query
     * @return void
     */
    protected function prepareWith($query): void
    {
        $query
            ->when(count($this->with), function (Builder $query) {
                foreach ($this->with as $item) {
                    if ($item['callback'] !== null) {
                        $query->with($item['relations'], $item['callback']);
                    } else {
                        $query->with($item['relations']);
                    }
                }
            })
            ->when(count($this->withWhereHas), function (Builder $query) {
                foreach ($this->withWhereHas as $item) {
                    if ($item['callback'] !== null) {
                        $query->withWhereHas($item['relations'], $item['callback']);
                    } else {
                        $query->withWhereHas($item['relations']);
                    }
                }
            });

        // reset with array for further operation
        $this->resetWith();
        $this->resetWithWhereHas();
    }

    /**
     * @param Builder $query
     * @param array $order
     * @return Builder
     */
    protected function applyOrderToQuery(Builder $query, array $order): Builder
    {
        if (count($order)) {
            foreach ($order as $column => $sort) {
                $query->orderBy($column, $sort);
            }
        }

        return $query;
    }
}
