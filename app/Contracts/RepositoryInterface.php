<?php

namespace App\Contracts;

use App\Support\WhereBuilder\GetterExpressionInterface;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * @return static
     */
    public function resetWith(): static;

    /**
     * @return static
     */
    public function resetWithWhereHas(): static;

    /**
     * Use this to have a fresh with collector
     *
     * @param array|string $relations
     * @param Closure|string|null $callback
     * @return static
     */
    public function newWith(array|string $relations, Closure|null|string $callback = null): static;

    /**
     * Use this to continue with collector
     *
     * @param array|string $relations
     * @param Closure|string|null $callback
     * @return static
     */
    public function with(array|string $relations, Closure|null|string $callback = null): static;

    /**
     * Use this to have a fresh withWhereHas collector
     *
     * @param array|string $relations
     * @param Closure|string|null $callback
     * @return static
     */
    public function newWithWhereHas(array|string $relations, Closure|null|string $callback = null): static;

    /**
     * Use this to continue withWhereHas collector
     *
     * @param array|string $relations
     * @param Closure|string|null $callback
     * @return static
     */
    public function withWhereHas(array|string $relations, Closure|null|string $callback = null): static;

    /**
     * @param GetterExpressionInterface|null $where
     * @return bool
     */
    public function exists(?GetterExpressionInterface $where = null): bool;

    /**
     * @param GetterExpressionInterface|null $where
     * @param bool $withTrashed
     * @return int
     */
    public function count(
        ?GetterExpressionInterface $where = null,
        bool                       $withTrashed = false
    ): int;

    /**
     * @param array $columns
     * @param GetterExpressionInterface|null $where
     * @param array $order
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return Collection
     */
    public function all(
        array                      $columns = ['*'],
        ?GetterExpressionInterface $where = null,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): Collection;

    /**
     * @param callable $callback
     * @param GetterExpressionInterface|null $where
     * @param int $count
     * @param array $order
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return bool
     */
    public function chunk(
        callable                   $callback,
        ?GetterExpressionInterface $where = null,
        int                        $count = 200,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): bool;

    /**
     * If $limit is less than zero, $offset will consider
     * otherwise offset will calculate automatically
     *
     * @param array $columns
     * @param GetterExpressionInterface|null $where
     * @param int|null $limit
     * @param int $page
     * @param array $order
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return LengthAwarePaginator
     */
    public function paginate(
        array                      $columns = ['*'],
        ?GetterExpressionInterface $where = null,
        ?int                       $limit = 15,
        int                        $page = 1,
        array                      $order = [],
        bool                       $withTrashed = false,
        bool                       $onlyTrashed = false
    ): LengthAwarePaginator;

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * @param $id
     * @param array $columns
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return Collection|Model|null
     */
    public function find(
        $id,
        array $columns = ['*'],
        bool $withTrashed = false,
        bool $onlyTrashed = false
    ): Collection|Model|null;

    /**
     * @param $id
     * @param array $columns
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return Collection|Model|null
     */
    public function findOrFail(
        $id,
        array $columns = ['*'],
        bool $withTrashed = false,
        bool $onlyTrashed = false
    ): Collection|Model|null;

    /**
     * @param GetterExpressionInterface $where
     * @param array $columns
     * @param bool $withTrashed
     * @param bool $onlyTrashed
     * @return Model|null
     */
    public function findWhere(
        GetterExpressionInterface $where,
        array                     $columns = ['*'],
        bool                      $withTrashed = false,
        bool                      $onlyTrashed = false
    ): Model|null;

    /**
     * @param array $data
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $data, array $values = []): mixed;

    /**
     * @param $id
     * @param array $data
     * @return int
     */
    public function update($id, array $data): int;

    /**
     * @param array $data
     * @param GetterExpressionInterface $where
     * @return int
     */
    public function updateWhere(array $data, GetterExpressionInterface $where): int;

    /**
     * @param $id
     * @param bool $permanent
     * @return mixed
     */
    public function delete($id, bool $permanent = false): mixed;

    /**
     * @param GetterExpressionInterface $where
     * @param bool $permanent
     * @return mixed
     */
    public function deleteWhere(GetterExpressionInterface $where, bool $permanent = false): mixed;
}
