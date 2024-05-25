<?php

namespace App\Http\Requests\Filters;

use App\Support\Filter;
use Illuminate\Http\Request;

class HomeBlogCommentFilter extends Filter
{
    /**
     * @var int|null
     */
    protected ?int $parentId;

    /**
     * @return int|null
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int|null $parentId
     * @return static
     */
    public function setParentId(?int $parentId): static
    {
        $this->parentId = is_numeric($parentId) && $parentId > 0 ? $parentId : null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $this->setParentId($request->integer('parent_id'));
    }
}
