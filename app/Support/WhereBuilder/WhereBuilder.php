<?php

namespace App\Support\WhereBuilder;

use Closure;
use Illuminate\Support\Traits\Conditionable;

class WhereBuilder implements WhereBuilderInterface
{
    use Conditionable;

    /**
     * @var array $where
     */
    protected array $where = [];

    /**
     * @var array $bindings
     */
    protected array $bindings = [];

    public function __construct(protected string $prefix = '')
    {
    }

    /**
     * @inheritDoc
     */
    public function whereEqual($column, $value): static
    {
        $this->_where($column, $value, '=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotEqual($column, $value): static
    {
        $this->_where($column, $value, '<>');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereGreaterThan($column, $value): static
    {
        $this->_where($column, $value, '>');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereGreaterThanEqual($column, $value): static
    {
        $this->_where($column, $value, '>=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLessThan($column, $value): static
    {
        $this->_where($column, $value, '<');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLessThanEqual($column, $value): static
    {
        $this->_where($column, $value, '<=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLike($column, $value, string $operand = '%{value}%'): static
    {
        $this->_whereLike($column, $value, $operand);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotLike($column, $value, string $operand = '%{value}%'): static
    {
        $this->_whereLike($column, $value, $operand, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIn($column, array $value): static
    {
        $this->_whereIn($column, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn($column, array $value): static
    {
        $this->_whereIn($column, $value, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNull($column): static
    {
        $this->_whereNull($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotNull($column): static
    {
        $this->_whereNull($column, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereBetween($column, $first, $second): static
    {
        $this->_whereBetween($column, $first, $second);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween($column, $first, $second): static
    {
        $this->_whereBetween($column, $first, $second, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereRegexp($column, $pattern): static
    {
        $this->_whereRegexp($column, $pattern);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereRaw(string $expression, array $bindings): static
    {
        $this->_whereRaw($expression, $bindings);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group(Closure $callback, ?string $prefix = ''): static
    {
        $this->_group($callback, $prefix);
        return $this;
    }

    public function orWhereEqual($column, $value): static
    {
        $this->_where($column, $value, '=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotEqual($column, $value): static
    {
        $this->_where($column, $value, '<>', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereGreaterThan($column, $value): static
    {
        $this->_where($column, $value, '>', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereGreaterThanEqual($column, $value): static
    {
        $this->_where($column, $value, '>=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLessThan($column, $value): static
    {
        $this->_where($column, $value, '<', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLessThanEqual($column, $value): static
    {
        $this->_where($column, $value, '<=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLike($column, $value, string $operand = '%{value}%'): static
    {
        $this->_whereLike($column, $value, $operand, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotLike($column, $value, string $operand = '%{value}%'): static
    {
        $this->_whereLike($column, $value, $operand, true, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereIn($column, array $value): static
    {
        $this->_whereIn($column, $value, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotIn($column, array $value): static
    {
        $this->_whereIn($column, $value, true, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNull($column): static
    {
        $this->_whereNull($column, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotNull($column): static
    {
        $this->_whereNull($column, true, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereBetween($column, $first, $second): static
    {
        $this->_whereBetween($column, $first, $second, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotBetween($column, $first, $second): static
    {
        $this->_whereBetween($column, $first, $second, true, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereRegexp($column, $pattern): static
    {
        $this->_whereRegexp($column, $pattern, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereRaw(string $expression, array $bindings): static
    {
        $this->_whereRaw($expression, $bindings, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orGroup(Closure $callback, ?string $prefix = ''): static
    {
        $this->_group($callback, $prefix, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<>');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereGreaterThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereGreaterThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLessThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLessThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<=');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second, true);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<>', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereGreaterThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereGreaterThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLessThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLessThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second, true, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second, false, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orWhereNotBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second, true, 'or');
        return $this;
    }

    public function build(): GetterExpression
    {
        $statement = '';
        $len = count($this->where);
        for ($idx = 0; $idx < $len; $idx++) {
            $statement .= $this->where[$idx]['query'];
            if ($idx < $len - 1) {
                $statement .= ' ' . strtoupper($this->where[$idx]['bool']) . ' ';
            }
        }
        $bindings = $this->bindings;

        return new GetterExpression($statement, $bindings);
    }

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        $this->where = [];
        $this->bindings = [];

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @param string $boolean
     * @return void
     */
    protected function _where($column, $value, $operator, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $val) {
                $this->_whereOne($val, $value, $operator, $boolean);
            }
        } else {
            $this->_whereOne($column, $value, $operator, $boolean);
        }
    }

    /**
     * @param $column
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereNull($column, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $value) {
                $this->_whereNullOne($value, $not, $boolean);
            }
        } else {
            $this->_whereNullOne($column, $not, $boolean);
        }
    }

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereBetween($column, $first, $second, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $value) {
                $this->_whereBetweenOne($value, $first, $second, $not, $boolean);
            }
        } else {
            $this->_whereBetweenOne($column, $first, $second, $not, $boolean);
        }
    }

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereLike($column, $value, string $operand, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $val) {
                $this->_whereLikeOne($val, $value, $operand, $not, $boolean);
            }
        } else {
            $this->_whereLikeOne($column, $value, $operand, $not, $boolean);
        }
    }

    /**
     * @param $column
     * @param $value
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereIn($column, $value, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $val) {
                $this->_whereInOne($val, $value, $not, $boolean);
            }
        } else {
            $this->_whereInOne($column, $value, $not, $boolean);
        }
    }

    /**
     * @param $callback
     * @param $prefix
     * @param string $boolean
     * @return void
     */
    protected function _group($callback, $prefix, string $boolean = 'and'): void
    {
        $prefix = !is_null($prefix) ? (mb_strlen($prefix) ? $prefix : $this->prefix) : '';
        $wb = new static($prefix);
        $callback($wb);
        $built = $wb->build();

        $this->where[] = [
            'query' => '(' . $built->getStatement() . ')',
            'bool' => $boolean,
        ];
        foreach ($built->getBindings() as $binding) {
            $this->bindings[] = $binding;
        }
    }

    /**
     * @param $column
     * @param $pattern
     * @param string $boolean
     * @return void
     */
    protected function _whereRegexp($column, $pattern, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $value) {
                $this->_whereRegexpOne($value, $pattern, $boolean);
            }
        } else {
            $this->_whereRegexpOne($column, $pattern, $boolean);
        }
    }

    /**
     * @param string $expression
     * @param array $bindings
     * @param string $boolean
     * @return void
     */
    protected function _whereRaw(string $expression, array $bindings, string $boolean = 'and'): void
    {
        $this->where[] = [
            'query' => '(' . $expression . ')',
            'bool' => $boolean,
        ];

        foreach ($bindings as $binding) {
            $this->bindings[] = $binding;
        }
    }

    /**
     * @param $first
     * @param $second
     * @param $operator
     * @param string $boolean
     * @return void
     */
    protected function _whereColumn($first, $second, $operator, string $boolean = 'and'): void
    {
        if (is_array($first)) {
            foreach ($first as $value) {
                $this->_whereColumnOne($value, $second, $operator, $boolean);
            }
        } else {
            $this->_whereColumnOne($first, $second, $operator, $boolean);
        }
    }

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereBetweenColumn($column, $first, $second, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($column)) {
            foreach ($column as $value) {
                $this->_whereBetweenColumnOne($value, $first, $second, $not, $boolean);
            }
        } else {
            $this->_whereBetweenColumnOne($column, $first, $second, $not, $boolean);
        }
    }

    /**
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereLikeColumn($first, $second, bool $not = false, string $boolean = 'and'): void
    {
        if (is_array($first)) {
            foreach ($first as $value) {
                $this->_whereLikeColumnOne($value, $second, $not, $boolean);
            }
        } else {
            $this->_whereLikeColumnOne($first, $second, $not, $boolean);
        }
    }

    /**
     * @param $column
     * @param $value
     * @param $operator
     * @param string $boolean
     * @return void
     */
    protected function _whereOne($column, $value, $operator, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ' ' . $operator . ' ?',
            'bool' => $boolean,
        ];
        $this->bindings[] = $value;
    }

    /**
     * @param $column
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereNullOne($column, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ' IS' . ($not ? ' NOT' : '') . ' NULL',
            'bool' => $boolean,
        ];
    }

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereBetweenOne($column, $first, $second, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ($not ? ' NOT' : '') . ' BETWEEN ? AND ?',
            'bool' => $boolean,
        ];
        $this->bindings[] = $first;
        $this->bindings[] = $second;
    }

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereLikeOne($column, $value, string $operand, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ($not ? ' NOT' : '') . ' LIKE ?',
            'bool' => $boolean,
        ];

        $value = str_replace('%', '\\%', $value);
        $value = str_replace('_', '\\_', $value);

        $this->bindings[] = str_replace('{value}', $value, $operand);
    }

    /**
     * @param $column
     * @param $value
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereInOne($column, $value, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $values = array_fill(0, count($value), '?');
        $values = implode(',', $values);
        $this->where[] = [
            'query' => $column . ($not ? ' NOT' : '') . ' IN (' . $values . ')',
            'bool' => $boolean,
        ];
        foreach ($value as $val) {
            $this->bindings[] = $val;
        }
    }

    /**
     * @param $column
     * @param $pattern
     * @param string $boolean
     * @return void
     */
    protected function _whereRegexpOne($column, $pattern, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ' REGEXP \'' . $pattern . '\'',
            'bool' => $boolean,
        ];
    }


    protected function _whereColumnOne($first, $second, $operator, string $boolean = 'and'): void
    {
        $first = $this->getColumnWithPrefix($first);
        $second = $this->getColumnWithPrefix($second);
        $this->where[] = [
            'query' => $first . ' ' . $operator . ' ' . $second,
            'bool' => $boolean,
        ];
    }

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereBetweenColumnOne($column, $first, $second, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $first = $this->getColumnWithPrefix($first);
        $second = $this->getColumnWithPrefix($second);
        $this->where[] = [
            'query' => $column . ($not ? ' NOT' : '') . ' BETWEEN ' . $first . ' AND ' . $second,
            'bool' => $boolean,
        ];
    }

    /**
     * @param $first
     * @param $second
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereLikeColumnOne($first, $second, bool $not = false, string $boolean = 'and'): void
    {
        $first = $this->getColumnWithPrefix($first);
        $second = $this->getColumnWithPrefix($second);
        $this->where[] = [
            'query' => $first . ($not ? ' NOT' : '') . ' LIKE ' . $second,
            'bool' => $boolean,
        ];
    }

    /**
     * @param $column
     * @return string
     */
    protected function getColumnWithPrefix($column): string
    {
        return mb_strlen($this->prefix) &&
        mb_strpos($column, '.') === false &&
        mb_strpos($column, '(') === false
            ? $this->prefix . '.' . $column
            : $column;
    }
}
