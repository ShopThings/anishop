<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BrandService extends Service implements BrandServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected BrandRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBrands(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('brands');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'latin_name',
                'escaped_name',
                'keywords',
            ], $search);
        });

        return $this->repository
            ->newWith(['image', 'creator', 'updater', 'deleter'])
            ->paginate(
                where: $where->build(),
                limit: $filter->getLimit(),
                page: $filter->getPage(),
                order: $filter->getOrder()
            );
    }

    /**
     * @inheritDoc
     */
    public function getBrandsCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getPublishedBrands(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('brands');
        $where
            ->whereEqual('is_deletable', DatabaseEnum::DB_YES)
            ->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository
            ->newWith('image')
            ->paginate(
                columns: ['id', 'name', 'latin_name', 'slug'],
                where: $where->build(),
                limit: $filter->getLimit(),
                page: $filter->getPage(),
                order: $filter->getOrder()
            );
    }

    /**
     * @inheritDoc
     */
    public function getSliderBrands(): Collection
    {
        $where = new WhereBuilder('brands');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES)
            ->whereEqual('show_in_slider', DatabaseEnum::DB_YES);

        return $this->repository
            ->newWith('image')
            ->all(
                columns: ['id', 'name', 'latin_name', 'slug'],
                where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

        $attrs = [
            'name' => $attributes['name'],
            'latin_name' => $attributes['latin_name'],
            'escaped_name' => NumberConverter::toEnglish($attributes['name']),
            'image_id' => $attributes['image'],
            'keywords' => $attributes['keywords'],
            'show_in_slider' => to_boolean($attributes['show_in_slider']),
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

        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
        }
        if (isset($attributes['latin_name'])) {
            $updateAttributes['latin_name'] = $attributes['latin_name'];
        }
        if (isset($attributes['escaped_name'])) {
            $updateAttributes['escaped_name'] = $attributes['escaped_name'];
        }
        if (isset($attributes['image'])) {
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
            $updateAttributes['image_id'] = $attributes['image'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['show_in_slider'])) {
            $updateAttributes['show_in_slider'] = to_boolean($attributes['show_in_slider']);
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
}
