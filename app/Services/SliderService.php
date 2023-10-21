<?php

namespace App\Services;

use App\Repositories\Contracts\SliderRepositoryInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use function App\Support\Helper\to_boolean;

class SliderService extends Service implements SliderServiceInterface
{
    public function __construct(
        protected SliderRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getSliders(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('sliders');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
        });

        return $this->repository->paginate(
            where: $where->build(), page: $page, limit: $limit, order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'slider_place_id' => $attributes['slider_place'],
            'title' => $attributes['title'],
            'priority' => $attributes['priority'] ?? 0,
            'options' => $attributes['options'] ?? [],
            'is_published' => to_boolean($attributes['is_published']),
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['slider_place'])) {
            $updateAttributes['slider_place_id'] = $attributes['slider_place'];
        }
        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['priority'])) {
            $updateAttributes['priority'] = $attributes['priority'];
        }
        if (isset($attributes['options'])) {
            $updateAttributes['options'] = $attributes['options'];
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }
        if (isset($attributes['is_deletable'])) {
            $updateAttributes['is_deletable'] = to_boolean($attributes['is_deletable']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function modifySliderItems(int $sliderId, array $slides): Collection
    {
        if (!count($slides))
            throw new InvalidArgumentException('وارد نمودن حداقل یک اسلاید اجباری می‌باشد.');

        foreach ($slides as &$item) {
            $item['slider_id'] = $sliderId;
        }

        return $this->repository->updateOrCreateItems($slides);
    }
}
