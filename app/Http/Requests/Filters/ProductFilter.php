<?php

namespace App\Http\Requests\Filters;

use App\Support\Filter;
use Illuminate\Http\Request;

class ProductFilter extends Filter
{
    /**
     * @var array|null
     */
    protected ?array $ids = null;

    /**
     * @return array|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    /**
     * @param int|null $ids
     * @return static
     */
    public function setIds(array $ids): static
    {
        $this->ids = count($ids) ? $ids : null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        parent::reset();

        $this->ids = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function init(Request $request): void
    {
        parent::init($request);

        $ids = $request->input('ids', []);
        $this->setIds(is_array($ids) ? $ids : []);
    }
}
