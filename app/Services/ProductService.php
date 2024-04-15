<?php

namespace App\Services;

use App\Enums\Products\ChangeMultipleProductPriceTypesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Requests\Filters\HomeProductSideFilter;
use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Converters\NumberConverter;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ProductService extends Service implements ProductServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected ProductRepositoryInterface $repository,
        protected UnitRepositoryInterface    $unitRepository,
        protected ColorRepositoryInterface   $colorRepository,
        protected SettingServiceInterface    $settingService,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProducts(
        Filter                    $filter,
        GetterExpressionInterface $where = null
    ): Collection|LengthAwarePaginator
    {
        return $this->repository
            ->newWith(['creator', 'updater', 'deleter'])
            ->getProductsSearchFilterPaginated(filter: $filter, where: $where);
    }

    /**
     * @inheritDoc
     */
    public function getSingleProduct(GetterExpressionInterface $where): ?Model
    {
        if (trim($where->getStatement()) === '') return null;
        return $this->repository->findWhere($where);
    }

    /**
     * @inheritDoc
     */
    public function getProductVariantByCode(string $code): ?Model
    {
        return $this->repository->getProductVariantByCode($code);
    }

    /**
     * @inheritDoc
     */
    public function getProductVariantsByCodes(array $codes): Collection
    {
        return $this->repository->getProductVariantsByCodes($codes);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredProducts(HomeProductFilter $filter): Collection|LengthAwarePaginator
    {
        $settingModel = $this->settingService->getSetting(SettingsEnum::PRODUCT_EACH_PAGE->value);
        $limit = $settingModel->setting_value ?: $settingModel->default_value;

        $filter->setLimit($limit);

        return $this->getProducts($filter);
    }

    /**
     * @inheritDoc
     */
    public function getFilterBrands(HomeProductSideFilter $filter): Collection
    {
        return $this->repository->getFilterBrands($filter);
    }

    /**
     * @inheritDoc
     */
    public function getFilterColorsAndSizes(HomeProductSideFilter $filter): Collection
    {
        return $this->repository->getFilterColorsAndSizes($filter);
    }

    /**
     * @inheritDoc
     */
    public function getFilterPriceRange(HomeProductSideFilter $filter): array
    {
        return $this->repository->getFilterPriceRange($filter);
    }

    /**
     * @inheritDoc
     */
    public function getDynamicFilters(HomeProductSideFilter $filter): Collection
    {
        // after getting filter, it might have duplicates for specific id,
        // and from below structure:
        // [
        //   [
        //     'id' => value...,
        //     'title' => value...,
        //     'type' => value...,
        //     'attribute_value_id' => value...,
        //     'attribute_value' => value...,
        //   ],
        //   ...
        // ]
        // we want to get following structure instead:
        // [
        //   [
        //     'id' => value...,
        //     'title' => value...,
        //     'type' => value...,
        //     'values' => [
        //       'id' => value..., // alias of 'attribute_value_id'
        //       'value' => value..., // alias of 'attribute_value'
        //     ],
        //   ],
        //   ...
        // ]
        // and below codes will do that
        return $this->repository->getDynamicFilters($filter)
            // 'id' is important, so preserve keys in collection
            ->groupBy('id')->map(function ($groupedItems) {
                return [
                    'id' => $groupedItems->first()['id'],
                    'title' => $groupedItems->first()['title'],
                    'type' => $groupedItems->first()['type'],
                    'values' => $groupedItems->pluck(['attribute_value_id', 'attribute_value'])
                        ->map(function ($item) {
                            return [
                                'id' => $item['attribute_value_id'],
                                'value' => $item['attribute_value'],
                            ];
                        })
                        ->toArray(),
                ];
            });
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

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
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
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
    public function createGalley(int $productId, array $images): bool
    {
        $images = $this->_refineGalleryImages($images);
        return $this->repository->createGallery($productId, $images);
    }

    /**
     * @inheritDoc
     */
    public function createRelatedProducts(int $productId, array $products): bool
    {
        return $this->repository->createRelatedProducts($productId, $products);
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
     * @inheritDoc
     */
    public function updateBatchInfo(array $ids, array $attributes): bool
    {
        $updateAttributes = [];

        if (isset($attributes['unit'])) {
            $updateAttributes['unit_name'] = $this->unitRepository->findOrFail(
                id: $attributes['unit'],
                columns: ['name']
            )->name;
        }
        if (isset($attributes['brand'])) {
            $updateAttributes['brand_id'] = $attributes['brand'];
        }
        if (isset($attributes['category'])) {
            $updateAttributes['category_id'] = $attributes['category'];
        }
        if (isset($attributes['is_available'])) {
            $updateAttributes['is_available'] = to_boolean($attributes['is_available']);
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }
        if (isset($attributes['is_commenting_allowed'])) {
            $updateAttributes['is_commenting_allowed'] = to_boolean($attributes['is_commenting_allowed']);
        }

        if (!count($updateAttributes)) return true;
        return !!$this->repository->update($ids, $updateAttributes);
    }

    /**
     * @inheritDoc
     */
    public function updateBatchPrice(
        array                               $ids,
        int                                 $percentage,
        ChangeMultipleProductPriceTypesEnum $changeType
    ): bool
    {
        return $this->repository->updatePriceUsingPercentage($ids, $percentage, $changeType);
    }

    /**
     * @param array $images
     * @return array
     */
    public function _refineGalleryImages(array $images): array
    {
        $refined = [];
        foreach ($images as $image) {
            $imageId = $this->getImageId($image);
            if (!empty($imageId)) {
                $refined[] = $imageId;
            }
        }
        return $refined;
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

                if (!count($refined[$counter]['children']))
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
