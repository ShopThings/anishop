<?php

namespace App\Support;

use App\Contracts\ServiceInterface;
use App\Support\Traits\ServiceTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

abstract class Service implements ServiceInterface
{
    use ServiceTrait;

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
        if ($permanent) {
            Gate::authorize('forceDelete', $this->repository->find($ids));
        }

        if (!count($ids)) return true;
        return (bool)$this->repository->deleteBatch($ids, $permanent);
    }
}
