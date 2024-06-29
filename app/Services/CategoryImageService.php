<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Services\Contracts\CategoryImageServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CategoryImageService extends Service implements CategoryImageServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected CategoryImageRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCategoryImages(Filter $filter): Collection|LengthAwarePaginator
    {
        return $this->repository
            ->newWith(['categoryImage', 'categoryImage.image'])
            ->getCategoryImagesSearchFilterPaginated(filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

        $attrs = [
            'category_id' => $attributes['category'],
            'image_id' => $attributes['image'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }
        if (isset($attributes['image'])) {
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
            $updateAttributes['image_id'] = $attributes['image'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
