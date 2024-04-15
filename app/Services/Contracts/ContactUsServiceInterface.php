<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ContactUsServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getContacts(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param $userId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getUserContacts($userId, Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getNotSeenContactsCount(): int;

    /**
     * @param $userId
     * @return int
     */
    public function getUserContactsCount($userId): int;

    /**
     * @param $id
     * @return bool
     */
    public function deleteUserContactById($id): bool;
}
