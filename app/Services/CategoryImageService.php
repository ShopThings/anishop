<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Services\Contracts\CategoryImageServiceInterface;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class CategoryImageService extends Service implements CategoryImageServiceInterface
{
    public function __construct(
        protected CategoryImageRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCategoryImages(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getCategoryImagesSearchFilterPaginated(
            search: trim($searchText ?? ''),
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
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
            $updateAttributes['image_id'] = $attributes['image'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
