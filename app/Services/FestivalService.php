<?php

namespace App\Services;

use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Services\Contracts\FestivalServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class FestivalService extends Service implements FestivalServiceInterface
{
    public function __construct(
        protected FestivalRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getFestivals(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('festivals');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query
                ->when(Verta::createFromFormat($search, 'Y-m-d'), function (WhereBuilderInterface $q, $date) {
                    $q
                        ->orWhereGreaterThanEqual('start_at', $date)
                        ->orWhereLessThanEqual('end_at', $date);
                })
                ->orWhereLike('title', $search);
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
            'title' => $attributes['title'],
            'start_at' => isset($attributes['start_at'])
                ? Verta::createFromFormat($attributes['start_at'], 'Y-m-d H:i:s')
                : null,
            'end_at' => isset($attributes['end_at'])
                ? Verta::createFromFormat($attributes['end_at'], 'Y-m-d H:i:s')
                : null,
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

        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['start_at'])) {
            $updateAttributes['start_at'] = Verta::createFromFormat($attributes['start_at'], 'Y-m-d H:i:s');
        }
        if (isset($attributes['end_at'])) {
            $updateAttributes['end_at'] = Verta::createFromFormat($attributes['end_at'], 'Y-m-d H:i:s');
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getFestivalProducts(
        int     $festivalId,
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getFestivalProductsSearchFilterPaginated(
            festivalId: $festivalId,
            search: $searchText,
            limit: $limit,
            page: $page,
            order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function addProductToFestival($productId, $festivalId, $discountPercentage): ?Model
    {
        return $this->repository->create([
            'product_id' => $productId,
            'festival_id' => $festivalId,
            'discount_percentage' => $discountPercentage,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function removeProductFromFestival($productId, $festivalId): bool
    {
        $where = new WhereBuilder('product_festivals');
        $where->whereEqual('product_id', $productId)
            ->whereEqual('festival_id', $festivalId);

        return $this->repository->deleteWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function addCategoryToFestival($categoryId, $festivalId, $discountPercentage): Collection
    {
        $productIds = $this->_getCategoryProductsIds($categoryId);
        $products = collect();

        foreach ($productIds as $id) {
            $tmp = $this->addProductToFestival($id, $festivalId, $discountPercentage);

            if (!is_null($tmp))
                $products->add($tmp);
        }

        return $products;
    }

    /**
     * @inheritDoc
     */
    public function removeCategoryFromFestival($categoryId, $festivalId): bool
    {
        $productIds = $this->_getCategoryProductsIds($categoryId);
        $where = new WhereBuilder('product_festivals');
        $where->whereIn('product_id', $productIds);

        return $this->repository->deleteWhere($where->build());
    }

    /**
     * @param $categoryId
     * @return array
     */
    protected function _getCategoryProductsIds($categoryId): array
    {
        $where = new WhereBuilder('products');
        $where->whereEqual('category_id', $categoryId);

        $products = $this->repository->all(
            columns: ['id'],
            where: $where->build()
        );

        $ids = [];
        $products->each(function ($item) use (&$ids) {
            $ids[] = $item['id'];
        });

        return $ids;
    }
}
