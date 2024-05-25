<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ComplaintServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getComplaints(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return int
     */
    public function getComplaintsCount(): int;

    /**
     * @return int
     */
    public function getNotSeenComplaintsCount(): int;
}
