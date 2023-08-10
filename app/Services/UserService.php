<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class UserService extends Service implements UserServiceInterface
{
    public function __construct(protected UserRepositoryInterface $repository)
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
            search: trim($searchText ?? ''),
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteUser($id, bool $permanent = false): bool
    {
        if ($permanent) {
            Gate::authorize('forceDelete', $this->repository->find($id));
        }

        return (bool)$this->repository->delete($id, $permanent);
    }

    /**
     * @inheritDoc
     */
    public function batchDelete(array $ids, bool $permanent = false): bool
    {
        if ($permanent) {
            Gate::authorize('forceDelete', $this->repository->find($ids));
        }

        if (!count($ids)) return true;
        return (bool)$this->repository->deleteBatch($ids, $permanent);
    }
}
