<?php

namespace App\Http\Requests\Filters;

use App\Enums\Products\ProductOrderTypesEnum;
use App\Support\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HomeProductFilter extends Filter
{
    /**
     * @var string|null
     */
    protected ?string $color = null;

    /**
     * @var string|null
     */
    protected ?string $size = null;

    /**
     * @var int|null
     */
    protected ?int $brand = null;

    /**
     * @var array
     */
    protected array $colors = [];

    /**
     * @var array
     */
    protected array $sizes = [];

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
     * @var int|null
     */
    protected ?int $festival = null;

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return static
     */
    public function setColor(?string $color): static
    {
        $this->color = $color && $color >= 1 ? $color : null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param string|null $size
     * @return static
     */
    public function setSize(?string $size): static
    {
        $this->size = $size && $size >= 1 ? $size : null;
        return $this;
    }

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
     * @return array
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * @param array $colors
     * @return static
     */
    public function setColors(array $colors): static
    {
        $temp = [];
        foreach ($colors as $color) {
            if (is_string($color)) {
                $temp[] = $color;
            }
        }
        $this->colors = $temp;

        return $this;
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     * @return static
     */
    public function setSizes(array $sizes): static
    {
        $temp = [];
        foreach ($sizes as $size) {
            if (is_string($size)) {
                $temp[] = $size;
            }
        }
        $this->sizes = $temp;

        return $this;
    }

    /**
     * @return array
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
     * @param string|null $filters
     * @return static
     */
    public function setDynamicFilters(?string $filters): static
    {
        $this->dynamicFilters = json_decode($filters, true);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFestival(): ?int
    {
        return $this->festival;
    }

    /**
     * @param int $festival
     * @return static
     */
    public function setFestival(int $festival): static
    {
        if ($festival > 0) {
            $this->festival = $festival;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->color = null;
        $this->size = null;
        $this->brand = null;
        $this->colors = [];
        $this->sizes = [];
        $this->brands = [];
        $this->category = null;
        $this->priceRange = [];
        $this->productOrder = ProductOrderTypesEnum::NEWEST;
        $this->isSpecial = false;
        $this->onlyAvailable = false;
        $this->isAvailable = true;
        $this->dynamicFilters = null;
        $this->festival = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setColor($request->string('color'));
        $this->setSize($request->string('size'));
        $this->setBrand($request->integer('brand'));
        $this->setCategory($request->integer('category'));
        $this->setProductOrder($request->enum('order', ProductOrderTypesEnum::class));
        $this->setIsSpecial($request->boolean('is_special'));
        $this->setOnlyAvailable($request->boolean('only_available'));
        $this->setDynamicFilters($request->input('dynamic_filters'));
        $this->setFestival($request->integer('festival'));

        // set colors
        $colors = $request->input('colors', []);
        if (is_array($colors) || is_string($colors)) {
            $this->setColors(Arr::wrap($colors));
        }

        // set sizes
        $sizes = $request->input('sizes', []);
        if (is_array($sizes) || is_string($sizes)) {
            $this->setSizes(Arr::wrap($sizes));
        }

        // set brands
        $brands = $request->input('brands', []);
        if (is_array($brands) || is_numeric($brands)) {
            $this->setBrands(Arr::wrap($brands));
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
