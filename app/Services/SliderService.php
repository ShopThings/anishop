<?php

namespace App\Services;

use App\Enums\Products\ProductOrderTypesEnum;
use App\Enums\Sliders\SliderItemOptionsEnum;
use App\Enums\Sliders\SliderOptionsEnum;
use App\Enums\Sliders\SliderPlacesEnum;
use App\Http\Requests\Filters\HomeProductFilter;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ProductResource;
use App\Models\Slider;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SliderRepositoryInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\FileServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SliderService extends Service implements SliderServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected SliderRepositoryInterface  $repository,
        protected BlogRepositoryInterface    $blogRepository,
        protected ProductRepositoryInterface $productRepository,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getSliders(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('sliders');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('title', $search);
        });

        return $this->repository
            ->newWith(['creator', 'updater', 'deleter'])
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
    public function getSlidersCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getSlider(SliderPlacesEnum|array $place, bool $withUnpublished = false): Collection
    {
        if (is_array($place)) {
            $place = collect($place)->filter(fn($item) => $item instanceof SliderPlacesEnum)->toArray();
        }

        return $this->repository->getSlider($place, $withUnpublished);
    }

    /**
     * @inheritDoc
     */
    public function getMainSlider(): Collection
    {
        $slider = $this->getSlider(SliderPlacesEnum::MAIN)->first();
        if (null === $slider) return collect();

        /**
         * @var FileServiceInterface $fileService
         */
        $fileService = app()->get(FileServiceInterface::class);

        /**
         * @var Collection $slides
         */
        $slides = $slider->items->pluck('options');
        return $slides->map(function ($item) use ($fileService) {
            $file = $fileService->find($item[SliderItemOptionsEnum::IMAGE->value], true);

            return [
                'image' => $file->full_path ?? null,
                'link' => $item[SliderItemOptionsEnum::LINK->value],
            ];
        });
    }

    /**
     * @inheritDoc
     */
    public function getAmazingOfferSlider(): Collection
    {
        $slider = $this->getSlider(SliderPlacesEnum::AMAZING_OFFER)->first();
        if (null === $slider) return collect();

        /**
         * @var ProductServiceInterface $productService
         */
        $productService = app()->get(ProductServiceInterface::class);

        /**
         * @var Collection $slides
         */
        $slides = $slider->items->pluck('options');
        return $slides->map(function ($item) use ($productService) {
            if (!isset($item[SliderItemOptionsEnum::PRODUCT_ID->value])) return null;

            $item = $productService->getById($item[SliderItemOptionsEnum::PRODUCT_ID->value]);
            return $item && $item['is_published'] ? $item : null;
        })->filter(fn($item) => null !== $item);
    }

    /**
     * @inheritDoc
     */
    public function getAllMainSliders(): Collection
    {
        $sliders = $this->getSlider([SliderPlacesEnum::MAIN_SLIDERS, SliderPlacesEnum::MAIN_SLIDER_IMAGES]);

        if ($sliders->isEmpty()) return collect();

        /**
         * @var ProductServiceInterface $productService
         */
        $productService = app()->get(ProductServiceInterface::class);
        /**
         * @var FileServiceInterface $fileService
         */
        $fileService = app()->get(FileServiceInterface::class);

        return $sliders
            ->sortBy('priority', SORT_ASC)
            ->map(function ($item) use ($productService, $fileService) {
                if ($item->place_in->value === SliderPlacesEnum::MAIN_SLIDER_IMAGES->value) {
                    $slides = $item->items
                        ->sortBy('priority', SORT_ASC)
                        ->map(function ($slide) use ($fileService) {
                            // second parameter will search on id of an image too
                            $file = $fileService->find($slide->options[SliderItemOptionsEnum::IMAGE->value], true);

                            return [
                                'id' => $slide->id,
                                'image' => $file?->full_path,
                                'link' => $slide->options[SliderItemOptionsEnum::LINK->value],
                            ];
                        });
                } else {
                    $options = $this->validateSliderOptions($item->options);

                    $filter = new HomeProductFilter();
                    $filter->reset();

                    $filter->setBrand($options['brand']);
                    $filter->setCategory($options['category']);
                    $filter->setProductOrder(
                        $options['sort'] == 'asc'
                            ? ProductOrderTypesEnum::OLDEST
                            : ProductOrderTypesEnum::NEWEST
                    );
                    $filter->setIsSpecial($options['is_special']);
                    $filter->setLimit($options['count']);
                    $filter->setIsAvailable(true);

                    $slides = collect($productService->getProducts(filter: $filter)->items());
                }

                // make options
                $options = [];
                if (isset($item->options[SliderOptionsEnum::SHOW_ALL_LINK->value])) {
                    $options[SliderOptionsEnum::SHOW_ALL_LINK->value] = $item->options[SliderOptionsEnum::SHOW_ALL_LINK->value] ?? null;
                }
                if ($item->place_in->value === SliderPlacesEnum::MAIN_SLIDER_IMAGES->value) {
                    $options[SliderOptionsEnum::BESIDE_IMAGES->value] = $item->options[SliderOptionsEnum::BESIDE_IMAGES->value] ?? 1;
                }

                return [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'place' => $item->place_in,
                    'items' => $slides,
                    'options' => $options,
                ];
            });
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'place_in' => $attributes['slider_place'],
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
            $updateAttributes['place_in'] = $attributes['slider_place'];
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
    public function getSliderItems(Slider $slider): Collection
    {
        return $this->prepareSliderItems($slider->items()->orderBy('priority')->orderBy('id')->get());
    }

    /**
     * @inheritDoc
     */
    public function modifySliderItems(int $sliderId, array $slides): Collection
    {
        if (!count($slides))
            throw new InvalidArgumentException('وارد نمودن حداقل یک اسلاید اجباری می‌باشد.');

        $newSlides = [];
        $collectedSlides = collect($slides);

        $counter = 0;
        $collectedSlides
            ->sortBy('id')
            ->each(function ($item) use (&$newSlides, $sliderId, &$counter) {
                $newItem = $item;
                unset($newItem['id']);

                // store id of an image to prevent any error on move of that file
                if (isset($newItem[SliderItemOptionsEnum::IMAGE->value]['id'])) {
                    $newItem[SliderItemOptionsEnum::IMAGE->value] = $newItem[SliderItemOptionsEnum::IMAGE->value]['id'];
                }
                if (isset($newItem['blog']['id'])) {
                    $newItem[SliderItemOptionsEnum::BLOG_ID->value] = $newItem['blog']['id'];

                    unset($newItem[SliderItemOptionsEnum::IMAGE->value]);
                    unset($newItem[SliderItemOptionsEnum::LINK->value]);
                    unset($newItem['blog']);
                }
                if (isset($newItem['product']['id'])) {
                    $newItem[SliderItemOptionsEnum::PRODUCT_ID->value] = $newItem['product']['id'];

                    unset($newItem[SliderItemOptionsEnum::IMAGE->value]);
                    unset($newItem[SliderItemOptionsEnum::LINK->value]);
                    unset($newItem['product']);
                }

                $newSlides[] = [
                    'id' => isset($item['id']) && !empty($item['id']) ? $item['id'] : null,
                    'slider_id' => $sliderId,
                    'priority' => $counter++,
                    'options' => $newItem,
                ];
            });

        return $this->prepareSliderItems($this->repository->updateOrCreateItems($newSlides, $sliderId));
    }

    /**
     * @param array $options
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function validateSliderOptions(array $options): array
    {
        /**
         * @var BrandServiceInterface $brandService
         */
        $brandService = app()->get(BrandServiceInterface::class);
        /**
         * @var CategoryServiceInterface $categoryService
         */
        $categoryService = app()->get(CategoryServiceInterface::class);

        $hasBrand = false;
        $hasCategory = false;

        if (isset($options[SliderOptionsEnum::BRAND_ID->value])) {
            $hasBrand = $brandService->exists($options[SliderOptionsEnum::BRAND_ID->value]);
        }
        if (isset($options[SliderOptionsEnum::CATEGORY_ID->value])) {
            $hasCategory = $categoryService->exists($options[SliderOptionsEnum::CATEGORY_ID->value]);
        }

        $sort = $options[SliderOptionsEnum::ORDER_BY->value] ?? 'asc';
        $sort = !in_array($sort, ['asc', 'desc']) ? 'desc' : $sort;

        $count = $options[SliderOptionsEnum::COUNT->value];
        $count = $count <= 0 || $count > 20 ? 12 : $count;

        return [
            'brand' => $hasBrand ? $options[SliderOptionsEnum::BRAND_ID->value] : null,
            'category' => $hasCategory ? $options[SliderOptionsEnum::CATEGORY_ID->value] : null,
            'sort' => $sort,
            'is_special' => !!$options[SliderOptionsEnum::IS_SPECIAL->value],
            'count' => $count,
        ];
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    protected function prepareSliderItems(Collection $items): Collection
    {
        return $items->map(function ($item) {
            // Get the options array, modify it, and set it back
            $options = $item->options; // This should give you the array from the accessor
            if (is_array($options)) {
                if (isset($options[SliderItemOptionsEnum::IMAGE->value])) {
                    $options[SliderItemOptionsEnum::IMAGE->value] = $this->getImageFromId($options[SliderItemOptionsEnum::IMAGE->value]);
                }
                if (
                    isset($options[SliderItemOptionsEnum::BLOG_ID->value]) &&
                    is_numeric($options[SliderItemOptionsEnum::BLOG_ID->value])
                ) {
                    $options[SliderItemOptionsEnum::BLOG_ID->value] = new BlogResource($this->blogRepository->find($options[SliderItemOptionsEnum::BLOG_ID->value]));
                }
                if (
                    isset($options[SliderItemOptionsEnum::PRODUCT_ID->value]) &&
                    is_numeric($options[SliderItemOptionsEnum::PRODUCT_ID->value])
                ) {
                    $options[SliderItemOptionsEnum::PRODUCT_ID->value] = new ProductResource($this->productRepository->find($options[SliderItemOptionsEnum::PRODUCT_ID->value]));
                }
            }
            $item->options = $options; // Set the modified options back to the item

            return $item;
        })->sortBy('id');
    }
}
