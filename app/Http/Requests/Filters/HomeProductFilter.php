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
     * @var int|null
     */
    protected ?int $category = null;

    /**
     * @var ProductOrderTypesEnum
     */
    protected ProductOrderTypesEnum $productOrder = ProductOrderTypesEnum::NEWEST;

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

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->setBrand($request->integer('brand'));
        $this->setCategory($request->integer('category'));
        $this->setProductOrder($request->enum('order', ProductOrderTypesEnum::class));
        $this->setIsSpecial($request->boolean('is_spacial'));
        $this->setOnlyAvailable($request->boolean('only_available'));
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
     * It is different from <b>setIsAvailable</b> method because
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
     * It will check a simple availability check
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
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->brand = null;
        $this->category = null;
        $this->productOrder = ProductOrderTypesEnum::NEWEST;
        $this->isSpecial = false;
        $this->onlyAvailable = false;
        $this->isAvailable = true;

        return $this;
    }
}
