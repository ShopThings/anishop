<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Enums\Sliders\SliderPlacesEnum;
use Illuminate\Support\Collection;

interface SliderRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $items
     * @param int $sliderId
     * @return Collection
     */
    public function updateOrCreateItems(array $items, int $sliderId): Collection;

    /**
     * @param SliderPlacesEnum|array $place
     * @param bool $withUnpublished
     * @return Collection
     */
    public function getSlider(SliderPlacesEnum|array $place, bool $withUnpublished = false): Collection;
}
