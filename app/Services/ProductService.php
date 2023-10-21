<?php

namespace App\Services;

use App\Models\Color;
use App\Models\Unit;
use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use function App\Support\Helper\to_boolean;

class ProductService extends Service implements ProductServiceInterface
{
    public function __construct(
        protected ProductRepositoryInterface $repository,
        protected UnitRepositoryInterface    $unitRepository,
        protected ColorRepositoryInterface   $colorRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProducts(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        return $this->repository->getProductsSearchFilterPaginated(
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
            'brand_id' => $attributes['brand'],
            'category_id' => $attributes['category'],
            'title' => $attributes['title'],
            'escaped_title' => NumberConverter::toEnglish($attributes['title']),
            'image_id' => $attributes['image'] ?? null,
            'description' => $attributes['description'] ?? '',
            'properties' => $this->_refineProperties($attributes['properties'] ?? []),
            'quick_properties' => $this->_refineQuickProperties($attributes['quick_properties'] ?? []),
            'unit_name' => $this->unitRepository->find($attributes['unit'])?->name,
            'keywords' => $attributes['keywords'],
            'is_available' => to_boolean($attributes['is_available']),
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

        if (isset($attributes['brand'])) {
            $updateAttributes['brand_id'] = $attributes['brand'];
        }
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
        if (isset($attributes['properties'])) {
            $updateAttributes['properties'] = $attributes['properties'];
        }
        if (isset($attributes['quick_properties'])) {
            $updateAttributes['quick_properties'] = $attributes['quick_properties'];
        }
        if (isset($attributes['unit']) && $unit = $this->unitRepository->find($attributes['unit'])?->name) {
            $updateAttributes['unit_name'] = $unit;
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['is_available'])) {
            $updateAttributes['is_available'] = to_boolean($attributes['is_available']);
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

    /**
     * @inheritDoc
     */
    public function modifyProducts(int $productId, array $products): Model|Collection
    {
        $products = $this->_refineProducts($products);

        if (!count($products))
            throw new InvalidArgumentException('وارد نمودن حداقل یک محصول اجباری می‌باشد.');

        foreach ($products as &$product) {
            $product['product_id'] = $productId;
        }

        return $this->repository->updateOrCreateProducts($products);
    }

    /**
     * @param array $products
     * @return array
     */
    private function _refineProducts(array $products): array
    {
        $refined = [];
        foreach ($products as $product) {
            if ($product['color'] || $product['size'] || $product['guarantee']) {
                $tmp = $product;
                unset($tmp['color']);

                $color = $this->colorRepository->find($product['color']);
                $tmp['color_name'] = $color->name;
                $tmp['color_hex'] = $color->hex;

                $tmp['is_special'] = to_boolean($product['is_special']);
                $tmp['is_available'] = to_boolean($product['is_available']);
                $tmp['show_coming_soon'] = to_boolean($product['show_coming_soon']);
                $tmp['show_call_for_more'] = to_boolean($product['show_call_for_more']);
                $tmp['is_published'] = to_boolean($product['is_published']);

                $refined[] = $tmp;
            }
        }
        return $refined;
    }

    /**
     * @param array $properties
     * @return array
     */
    private function _refineProperties(array $properties): array
    {
        if (!count($properties)) return [];

        $refined = [];
        $counter = 0;
        foreach ($properties as $property) {
            if (
                (!isset($property['title']) || trim($property['title']) !== '') &&
                count($property['children'])
            ) {
                $refined[$counter]['title'] = $property['title'];
                $refined[$counter]['children'] = [];

                foreach ($property['children'] as $child) {
                    if (
                        (!isset($child['title']) || trim($child['title']) !== '') &&
                        count($child['tags'])
                    ) {
                        $refined[$counter]['children'][] = $property;
                    }
                }

                if(!count($refined[$counter]['children']))
                    unset($refined[$counter]);
                else
                    $counter++;
            }
        }
        return $refined;
    }

    /**
     * @param array $quickProperties
     * @return array
     */
    private function _refineQuickProperties(array $quickProperties): array
    {
        if (!count($quickProperties)) return [];

        $refined = [];
        foreach ($quickProperties as $property) {
            if (
                (!isset($property['title']) || trim($property['title']) !== '') &&
                count($property['tags'])
            ) {
                $refined[] = $property;
            }
        }
        return $refined;
    }
}
