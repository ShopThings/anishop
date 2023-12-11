<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductCommentServiceInterface extends ServiceInterface
{
    /**
     * @param int $productId
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getComments(int $productId, Filter $filter): Collection|LengthAwarePaginator;
}
