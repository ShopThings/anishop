<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface SliderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $items
     * @return Collection
     */
    public function updateOrCreateItems(array $items): Collection;
}
