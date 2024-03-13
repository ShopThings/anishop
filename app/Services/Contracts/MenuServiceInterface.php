<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Menus\MenuPlacesEnum;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface MenuServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getMenus(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param MenuPlacesEnum $placeIn
     * @return Collection
     */
    public function getHomeMenus(MenuPlacesEnum $placeIn): Collection;

    /**
     * @param int $menuId
     * @param array $menus
     * @return Collection
     */
    public function modifyMenuItems(int $menuId, array $menus): Collection;
}
