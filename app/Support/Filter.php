<?php

namespace App\Support;

use App\Support\Traits\OrderingTrait;
use Illuminate\Http\Request;

class Filter
{
    use OrderingTrait;

    /**
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * @var int
     */
    protected int $offset = 0;

    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var array|string[]
     */
    protected array $order = ['id' => 'desc'];

    /**
     * @var string|null
     */
    protected ?string $searchText = null;

    public function __construct(?Request $request = null)
    {
        if (is_null($request)) $request = request();

        $offset = $request->integer('offset');
        $page = $request->integer('page');
        $orderColumn = $request->string('order', 'id')->toString();
        $orderSort = $request->string('sort', 'desc')->toString();

        //

        $this->setLimit($request->integer('limit'));

        if ($page) {
            $this->setOffset($page);
        } elseif ($offset) {
            $this->setOffset($offset);
        }

        if (!empty(trim($orderColumn)) && !empty(trim($orderSort))) {
            $this->setOrder([$orderColumn => $orderSort]);
        }

        $this->setSearchText($request->string('text')->toString());
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return static
     */
    public function setLimit(?int $limit): static
    {
        if ($limit > 0) $this->limit = $limit;
        else $this->limit = null;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return static
     */
    public function setOffset(int $offset): static
    {
        if ($offset >= 0) {
            $this->offset = $offset;
            $this->page = floor($offset / $this->limit) + 1;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return static
     */
    public function setPage(int $page): static
    {
        if ($page >= 1) {
            $this->page = $page;
            $this->offset = ($page - 1) * $this->limit;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getOrder(): array
    {
        return $this->convertOrdersColumnToArray($this->order);
    }

    /**
     * It should be like:
     *  [
     *    column => sort,
     *    ...
     *  ]
     *
     * @param array $order
     * @return static
     *
     * @example As an example:
     *  [
     *    'id' => 'desc',
     *    'name' => 'asc',
     *    ...
     *  ]
     */
    public function setOrder(array $order): static
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    /**
     * @param string|null $searchText
     * @return static
     */
    public function setSearchText(?string $searchText): static
    {
        if (null !== $searchText) $this->searchText = trim($searchText);
        else $this->searchText = null;
        return $this;
    }

    /**
     * @return static
     */
    public function reset(): static
    {
        $this->limit = null;
        $this->offset = 0;
        $this->page = 1;
        $this->order = ['id' => 'desc'];
        $this->searchText = null;

        return $this;
    }
}
