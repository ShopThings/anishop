<?php

namespace App\Support\Model;

use App\Contracts\BuildExpressionInterface;
use Illuminate\Database\Eloquent\Builder;

class Concat implements BuildExpressionInterface
{
    use AliasTrait;

    /**
     * @var Builder
     */
    private Builder $query;

    /**
     * @var array
     */
    private array $builder = [];

    /**
     * @var string
     */
    private string $statement = '';

    /**
     * @var string
     */
    private string $separator = ', ';

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;

        $this->statement .= 'CONCAT ';
    }

    /**
     * @param string ...$columns
     * @return static
     */
    public function columns(string ...$columns): static
    {
        $this->builder = $columns;
        return $this;
    }

    /**
     * @param string $separator
     * @param string ...$columns
     * @return static
     */
    public function columnsWithSeparator(string $separator, string ...$columns): static
    {
        $this->separator = $separator;
        $this->columns(...$columns);

        return $this;
    }

    /**
     * It must be call to build your concatenation phrase
     *
     * @return Builder
     */
    public function build(): Builder
    {
        $this->normalizeSeparator();

        $this->statement .= '(' . implode($this->separator, $this->builder) . ')';
        $this->statement .= $this->buildAlias($this->statement);
        $this->query->raw($this->statement);

        return $this->query;
    }

    /**
     * @return void
     */
    private function normalizeSeparator(): void
    {
        if ('' == trim($this->separator)) {
            $this->separator = ', ';
        }
    }
}
