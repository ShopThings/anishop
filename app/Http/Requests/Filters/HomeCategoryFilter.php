<?php

namespace App\Http\Requests\Filters;

use App\Support\Filter;
use Illuminate\Http\Request;

class HomeCategoryFilter extends Filter
{
    /**
     * @var int|null
     */
    protected ?int $parent = null;

    /**
     * @var int|null
     */
    protected ?int $ancestry = null;

    /**
     * @var int|null
     */
    protected ?int $level = null;

    /**
     * @var bool
     */
    protected bool $withChildren = false;

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setParent($request->integer('parent'));
        $this->setAncestry($request->integer('ancestry'));
        $this->setLevel($request->integer('level'));
        $this->setWithChildren($request->boolean('with_children'));
    }

    /**
     * @return int|null
     */
    public function getParent(): ?int
    {
        return $this->parent;
    }

    /**
     * @param int|null $parent
     * @return static
     */
    public function setParent(?int $parent): static
    {
        $this->parent = $parent && $parent >= 1 ? $parent : null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAncestry(): ?int
    {
        return $this->ancestry;
    }

    /**
     * @param int|null $ancestry
     * @return static
     */
    public function setAncestry(?int $ancestry): static
    {
        $this->ancestry = $ancestry && $ancestry >= 1 ? $ancestry : null;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int|null $level
     * @return static
     */
    public function setLevel(?int $level): static
    {
        $this->level = !is_null($level) && $level >= 0 ? $level : null;
        return $this;
    }

    /**
     * @return bool
     */
    public function getWithChildren(): bool
    {
        return $this->withChildren;
    }

    /**
     * @param bool $withChildren
     * @return static
     */
    public function setWithChildren(bool $withChildren): static
    {
        $this->withChildren = $withChildren && $withChildren >= 0 ? $withChildren : null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->parent = null;
        $this->ancestry = null;
        $this->level = null;
        $this->withChildren = false;

        return $this;
    }
}
