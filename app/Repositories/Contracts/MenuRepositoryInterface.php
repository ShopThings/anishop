<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Menus\MenuPlacesEnum;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface MenuRepositoryInterface extends RepositoryInterface
{
    /**
     * @param MenuPlacesEnum $placeIn
     * @return Collection
     */
    public function getHomeMenus(MenuPlacesEnum $placeIn): Collection;

    /**
     * @param array $items
     * @return Collection
     */
    public function updateOrCreateItems(array $items): Collection;
}
