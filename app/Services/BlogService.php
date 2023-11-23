<?php

namespace App\Services;

use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class BlogService extends Service implements BlogServiceInterface
{
    public function __construct(
        protected BlogRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getBlogs(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getBlogsSearchFilterPaginated(
            search: $searchText,
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
            'title' => $attributes['title'],
            'escaped_title' => NumberConverter::toEnglish($attributes['title']),
            'image_id' => $attributes['image'],
            'description' => $attributes['description'],
            'keywords' => $attributes['keywords'],
            'is_commenting_allowed' => to_boolean($attributes['is_commenting_allowed']),
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

        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }
        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
            $updateAttributes['escaped_title'] = NumberConverter::toEnglish($attributes['title']);
        }
        if (isset($attributes['image'])) {
            $updateAttributes['image_id'] = $attributes['image'];
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['is_commenting_allowed'])) {
            $updateAttributes['is_commenting_allowed'] = to_boolean($attributes['is_commenting_allowed']);
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
