<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Support\Collection;

interface CityServiceInterface extends ServiceInterface
{
    /**
     * @param int $provinceId
     * @return Collection
     */
    public function getCities(int $provinceId): Collection;

    /**
     * @param int $cityId
     * @param int $provinceId
     * @return bool
     */
    public function isCityInProvince(int $cityId, int $provinceId): bool;
}
