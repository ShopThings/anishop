<?php

namespace App\Repositories;

use App\Enums\Sliders\SliderPlacesEnum;
use App\Models\Slider;
use App\Models\SliderItem;
use App\Repositories\Contracts\SliderRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SliderRepository extends Repository implements SLiderRepositoryInterface
{
    public function __construct(
        Slider               $model,
        protected SliderItem $sliderItemModel
    )
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreateItems(array $items, int $sliderId): Collection
    {
        $modified = collect();

        $ids = array_filter(array_map(fn($item) => $item['id'], $items), fn($item) => !empty($item));
        $this->sliderItemModel->newQuery()
            ->where('slider_id', $sliderId)
            ->whereNotIn('id', $ids)
            ->delete();

        foreach ($items as $item) {
            if (
                isset($item['id']) &&
                (($founded = $this->sliderItemModel->findOrFail($item['id'])) instanceof Model)
            ) {
                $isUpdated = $founded->update($item);

                if ($isUpdated) {
                    $modified->add($this->sliderItemModel::query()->find($item['id']));
                }
            } else {
                $created = $this->sliderItemModel::create($item);

                if ($created instanceof Model) {
                    $modified->add($created);
                }
            }
        }

        return $modified;
    }

    /**
     * @inheritDoc
     */
    public function getSlider(SliderPlacesEnum|array $place, bool $withUnpublished = false): Collection
    {
        $place = Arr::wrap($place);

        // if there is no placement specified, empty collection is enough though
        if (!count($place)) return collect();

        $query = $this->model->newQuery();

        if (!$withUnpublished) {
            $query->published();
        }

        return $query
            ->whereIn('place_in', array_map(fn($item) => $item->value, $place))
            ->with(['items' => function ($query) use ($withUnpublished) {
                $query->orderBy('priority');
            }])
            ->orderBy('priority')
            ->get();
    }
}
