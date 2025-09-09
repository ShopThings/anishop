<?php

namespace App\Support\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Concat
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
    private string $separator = ', ';

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;

        $this->builder = [];
    }

    /**
     * @param string ...$columns
     * @return Builder
     */
    public function columns(string ...$columns): Builder
    {
        $columns = $this->escapeColumns($columns);
        $this->builder = $columns;

        return $this->build();
    }

    /**
     * It must be call to build your concatenation phrase
     *
     * @return Builder
     */
    protected function build(): Builder
    {
        $statement = 'CONCAT';
        $statement .= '(' . implode($this->separator, $this->builder) . ')';
        $statement = $this->buildAlias($statement);

        $this->query->selectRaw($statement);

        return $this->query;
    }

    /**
     * @param array $columns
     * @return array<string>
     */
    protected function escapeColumns(array $columns): array
    {
        return array_map(function ($column) {
            if (preg_match('#[\w\-]+#i', $column)) {
                return DB::getQueryGrammar()->wrap($column);
            }
            return DB::escape($column);
        }, $columns);
    }
}
