<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Sliders\SliderPlacesEnum;
use App\Support\Filter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SliderServiceInterface extends ServiceInterface
{
    /**
     * @param Filter $filter
     * @return Collection|LengthAwarePaginator
     */
    public function getSliders(Filter $filter): Collection|LengthAwarePaginator;

    /**
     * @param SliderPlacesEnum|array $place
     * @param bool $withUnpublished
     * @return Collection
     */
    public function getSlider(SliderPlacesEnum|array $place, bool $withUnpublished = false): Collection;

    /**
     * @return Collection
     */
    public function getMainSlider(): Collection;

    /**
     * @return Collection
     */
    public function getAmazingOfferSlider(): Collection;

    /**
     * @return Collection
     */
    public function getAllMainSliders(): Collection;

    /**
     * @param int $sliderId
     * @param array $slides
     * @return Collection
     */
    public function modifySliderItems(int $sliderId, array $slides): Collection;
}
