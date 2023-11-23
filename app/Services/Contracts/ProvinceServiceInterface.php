<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Support\Collection;

interface ProvinceServiceInterface extends ServiceInterface
{
    /**
     * @return Collection
     */
    public function getProvinces(): Collection;
}
