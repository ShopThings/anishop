<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SendMethodServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getMethods(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getHomeMethods(): Collection;

    /**
     * @param int $id
     * @param bool $publishedOne
     * @return Model|null
     */
    public function getSpecificMethod(int $id, bool $publishedOne = true): ?Model;
}
