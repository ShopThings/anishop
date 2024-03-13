<?php

namespace App\Http\Requests\Filters;

use App\Support\Filter;
use Illuminate\Http\Request;

class HomeProductSideFilter extends Filter
{
    /**
     * @var int|null
     */
    protected ?int $category = null;

    /**
     * @var int|null
     */
    protected ?int $festival = null;

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setCategory($request->integer('category'));
        $this->setFestival($request->integer('festival'));
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
     * @return int|null
     */
    public function getFestival(): ?int
    {
        return $this->festival;
    }

    /**
     * @param int|null $festival
     * @return static
     */
    public function setFestival(?int $festival): static
    {
        $this->festival = $festival && $festival >= 1 ? $festival : null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->category = null;
        $this->festival = null;

        return $this;
    }
}
