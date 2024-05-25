<?php

namespace App\Http\Requests\Filters;

use App\Enums\Products\ProductOrderTypesEnum;
use App\Support\Filter;
use Illuminate\Http\Request;

class HomeProductFilter extends Filter
{
    /**
     * @var int|null
     */
    protected ?int $brand = null;

    /**
     * @var array
     */
    protected array $brands = [];

    /**
     * @var int|null
     */
    protected ?int $category = null;

    /**
     * @var ProductOrderTypesEnum
     */
    protected ProductOrderTypesEnum $productOrder = ProductOrderTypesEnum::NEWEST;

    /**
     * @var array
     */
    protected array $priceRange = [];

    /**
     * @var bool
     */
    protected bool $isSpecial = false;

    /**
     * @var bool
     */
    protected bool $onlyAvailable = false;

    /**
     * @var bool
     */
    protected bool $isAvailable = true;

    /**
     * @var array|null
     */
    protected ?array $dynamicFilters = null;

    /**
     * @return int|null
     */
    public function getBrand(): ?int
    {
        return $this->brand;
    }

    /**
     * @param int|null $brand
     * @return static
     */
    public function setBrand(?int $brand): static
    {
        $this->brand = $brand && $brand >= 1 ? $brand : null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBrands(): array
    {
        return $this->brands;
    }

    /**
     * @param array $brands
     * @return static
     */
    public function setBrands(array $brands): static
    {
        $temp = [];
        foreach ($brands as $brand) {
            if (is_numeric($brand) && $brand >= 1) {
                $temp[] = $brand;
            }
        }
        $this->brands = $temp;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * @param int|null $category
     * @return static
     */
    public function setCategory(?int $category): static
    {
        $this->category = $category && $category >= 1 ? $category : null;
        return $this;
    }

    /**
     * @return ProductOrderTypesEnum
     */
    public function getProductOrder(): ProductOrderTypesEnum
    {
        return $this->productOrder;
    }

    /**
     * @param ProductOrderTypesEnum|null $productOrder
     * @return static
     */
    public function setProductOrder(?ProductOrderTypesEnum $productOrder): static
    {
        if (null !== $productOrder) $this->productOrder = $productOrder;
        else $this->productOrder = ProductOrderTypesEnum::NEWEST;
        return $this;
    }

    /**
     * @return array
     */
    public function getPriceRange(): array
    {
        return $this->priceRange;
    }

    /**
     * @param array $range
     * @return static
     */
    public function setPriceRange(array $range): static
    {
        $this->priceRange = $range;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSpecial(): bool
    {
        return $this->isSpecial;
    }

    /**
     * @param bool $isSpecial
     * @return static
     */
    public function setIsSpecial(bool $isSpecial): static
    {
        $this->isSpecial = $isSpecial;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOnlyAvailable(): bool
    {
        return $this->onlyAvailable;
    }

    /**
     * It is different from <strong>setIsAvailable()</strong> method because
     * this method will measure other factor like "stock_count" too
     *
     * @param bool $onlyAvailable
     * @return static
     */
    public function setOnlyAvailable(bool $onlyAvailable): static
    {
        $this->onlyAvailable = $onlyAvailable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAvailable(): bool
    {
        return $this->isAvailable;
    }

    /**
     * It'll do a simple availability check
     *
     * @param bool $isAvailable
     * @return static
     */
    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;
        return $this;
    }

    /**
     * It Should give following structure:
     * <pre>
     * [
     *   'attribute_1' => [ // attribute means 'attribute_id'
     *     'value-1', // selected value of attribute(actually it is 'attribute_value')
     *     'value-2', // more than one value is OPTIONAL and is mostly for multiselect attributes
     *     ...
     *   ],
     *   ...
     * ]
     * </pre>
     *
     * @return array|null
     */
    public function getDynamicFilters(): ?array
    {
        return $this->dynamicFilters;
    }

    /**
     * @param array|null $filters
     * @return static
     */
    public function setDynamicFilters(?array $filters): static
    {
        $this->dynamicFilters = $filters;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->brand = null;
        $this->brands = [];
        $this->category = null;
        $this->priceRange = [];
        $this->productOrder = ProductOrderTypesEnum::NEWEST;
        $this->isSpecial = false;
        $this->onlyAvailable = false;
        $this->isAvailable = true;
        $this->dynamicFilters = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setBrand($request->integer('brand'));
        $this->setCategory($request->integer('category'));
        $this->setProductOrder($request->enum('order', ProductOrderTypesEnum::class));
        $this->setIsSpecial($request->boolean('is_special'));
        $this->setOnlyAvailable($request->boolean('only_available'));
        $this->setDynamicFilters($request->input('dynamic_filters'));

        // set brands
        $brands = $request->input('brands', []);
        if (is_array($brands) || is_numeric($brands)) {
            $this->setBrands(is_array($brands) ? $brands : [$brands]);
        }

        // set price range
        $minPrice = $request->integer('min_price');
        $maxPrice = $request->integer('max_price');
        if ($minPrice !== 0 || $maxPrice !== 0 && $minPrice <= $maxPrice) {
            $this->setPriceRange([$minPrice, $maxPrice]);
        }

        $priceRange = $request->input('price_range', []);
        if (isset($priceRange[0], $priceRange[1])) {
            $this->setPriceRange([$priceRange[0], $priceRange[1]]);
        }
        //
    }
}
