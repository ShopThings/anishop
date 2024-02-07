<?php

namespace App\Support;

use App\Contracts\ServiceInterface;
use App\Support\Traits\ServiceTrait;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

abstract class Service implements ServiceInterface
{
    use ServiceTrait;

    /**
     * @inheritDoc
     */
    public function exists($id): bool
    {
        $where = new WhereBuilder();
        $where->whereEqual('id', $id);
        return $this->repository->exists($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getById($id): Collection|Model|null
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id, bool $permanent = false): bool
    {
        if ($permanent) {
            Gate::authorize('forceDelete', $this->repository->find($id));
        }

        return (bool)$this->repository->delete($id, $permanent);
    }

    /**
     * @inheritDoc
     */
    public function batchDeleteByIds(array $ids, bool $permanent = false): bool
    {
        if (!count($ids)) return true;

        if ($permanent) {
            Gate::authorize('forceDelete', $this->repository->find($ids));
        }

        return (bool)$this->repository->delete($ids, $permanent);
    }

    /**
     * @inheritDoc
     */
    public function batchDeleteBySlugs(array $slugs, bool $permanent = false): bool
    {
        $where = new WhereBuilder();
        $where->whereIn('slug', $slugs);
        $ids = $this->repository->all(columns: ['id'], where: $where->build())->pluck('id');
        return $this->batchDeleteByIds($ids->toArray(), $permanent);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        // empty implementation
        return null;
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        // empty implementation
        return null;
    }
}
