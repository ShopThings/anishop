<?php

namespace App\Support\WhereBuilder;

use Closure;
use Illuminate\Support\Traits\Conditionable;

class WhereBuilder implements WhereBuilderInterface
{
    use Conditionable;

    /**
     * @var array|string[]
     */
    protected array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>', '&~', 'is', 'is not',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

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
    public function whereEqual($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '=', $boolean);
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
     * @param $value
     * @param $operator
     * @param string $boolean
     * @return void
     */
    protected function _whereOne($column, $value, $operator, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $operator = $this->getValidOperator($operator);
        $this->where[] = [
            'query' => $column . ' ' . $operator . ' ?',
            'bool' => $this->getValidatedCondition($boolean),
        ];
        $this->bindings[] = $value;
    }

    /**
     * @param $operator
     * @return string
     */
    protected function getValidOperator($operator): string
    {
        if (!is_string($operator) || !in_array($operator, $this->operators, true)) return '=';
        return $operator;
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

    /**
     * @inheritDoc
     */
    public function orWhereEqual($column, $value): static
    {
        $this->_where($column, $value, '=', 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotEqual($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '<>', $boolean);
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
    public function whereGreaterThan($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '>', $boolean);
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
    public function whereGreaterThanEqual($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '>=', $boolean);
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
    public function whereLessThan($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '<', $boolean);
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
    public function whereLessThanEqual($column, $value, string $boolean = 'and'): static
    {
        $this->_where($column, $value, '<=', $boolean);
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
    public function whereLike(
        $column,
        $value,
        string $operand = '%{value}%',
        string $boolean = 'and',
        bool $not = false
    ): static
    {
        $this->_whereLike($column, $value, $operand, $not, $boolean);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];

        $value = str_replace('%', '\\%', $value);
        $value = str_replace('_', '\\_', $value);

        $this->bindings[] = str_replace('{value}', $value, $operand);
    }

    /**
     * @inheritDoc
     */
    public function orWhereLike($column, $value, string $operand = '%{value}%', bool $not = false): static
    {
        $this->_whereLike($column, $value, $operand, $not, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotLike($column, $value, string $operand = '%{value}%', string $boolean = 'and'): static
    {
        $this->_whereLike($column, $value, $operand, true, $boolean);
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
    public function whereIn($column, array $value, string $boolean = 'and', bool $not = false): static
    {
        $this->_whereIn($column, $value, $not, $boolean);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
        foreach ($value as $val) {
            $this->bindings[] = $val;
        }
    }

    /**
     * @inheritDoc
     */
    public function orWhereIn($column, array $value, bool $not = false): static
    {
        $this->_whereIn($column, $value, $not, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn($column, array $value, string $boolean = 'and'): static
    {
        $this->_whereIn($column, $value, true, $boolean);
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
    public function whereNull($column, string $boolean = 'and', bool $not = false): static
    {
        $this->_whereNull($column, $not, $boolean);
        return $this;
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
     * @param bool $not
     * @param string $boolean
     * @return void
     */
    protected function _whereNullOne($column, bool $not = false, string $boolean = 'and'): void
    {
        $column = $this->getColumnWithPrefix($column);
        $this->where[] = [
            'query' => $column . ' IS' . ($not ? ' NOT' : '') . ' NULL',
            'bool' => $this->getValidatedCondition($boolean),
        ];
    }

    /**
     * @inheritDoc
     */
    public function orWhereNull($column, bool $not = false): static
    {
        $this->_whereNull($column, $not, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotNull($column, string $boolean = 'and'): static
    {
        $this->_whereNull($column, true, $boolean);
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
    public function whereBetween($column, $first, $second, string $boolean = 'and', bool $not = false): static
    {
        $this->_whereBetween($column, $first, $second, $not, $boolean);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
        $this->bindings[] = $first;
        $this->bindings[] = $second;
    }

    /**
     * @inheritDoc
     */
    public function orWhereBetween($column, $first, $second, bool $not = false): static
    {
        $this->_whereBetween($column, $first, $second, $not, 'or');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotBetween($column, $first, $second, string $boolean = 'and'): static
    {
        $this->_whereBetween($column, $first, $second, true, $boolean);
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
    public function whereRegexp($column, $pattern, string $boolean = 'and'): static
    {
        $this->_whereRegexp($column, $pattern, $boolean);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
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
    public function whereRaw(string $expression, array $bindings): static
    {
        $this->_whereRaw($expression, $bindings);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];

        foreach ($bindings as $binding) {
            $this->bindings[] = $binding;
        }
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
    public function where($column, $operator = null, $value = null, string $boolean = 'and'): static
    {
        [$operator, $value] = $this->prepareOperatorAndValue($operator, $value);

        if (is_null($operator) && is_null($value)) {
            $this->whereNull($column, $boolean, $operator !== '=');
        }

        $this->_where($column, $value, $operator, $boolean);

        return $this;
    }

    /**
     * @param $operator
     * @param $value
     * @return array
     */
    protected function prepareOperatorAndValue($operator, $value): array
    {
        if ((!is_null($operator) && is_null($value)) || (is_null($operator) && !is_null($value))) {
            $operator = '=';
        }

        return [$operator, $value];
    }

    /**
     * @inheritDoc
     */
    public function orWhere($column, $operator = null, $value = null): static
    {
        $this->where($column, $operator, $value, 'or');
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
        foreach ($built->getBindings() as $binding) {
            $this->bindings[] = $binding;
        }
    }

    /**
     * @return GetterExpression
     */
    public function build(): GetterExpression
    {
        $statement = '';
        $len = count($this->where);
        for ($idx = 0; $idx < $len; $idx++) {
            if ($idx > 0) {
                $statement .= ' ' . strtoupper($this->where[$idx]['bool']) . ' ';
            }
            $statement .= $this->where[$idx]['query'];
        }
        $bindings = $this->bindings;

        return new GetterExpression($statement, $bindings);
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

    protected function _whereColumnOne($first, $second, $operator, string $boolean = 'and'): void
    {
        $first = $this->getColumnWithPrefix($first);
        $second = $this->getColumnWithPrefix($second);
        $this->where[] = [
            'query' => $first . ' ' . $operator . ' ' . $second,
            'bool' => $this->getValidatedCondition($boolean),
        ];
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
    public function whereNotEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<>');
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
    public function whereGreaterThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>');
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
    public function whereGreaterThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '>=');
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
    public function whereLessThanColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<');
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
    public function whereLessThanEqualColumn($first, $second): static
    {
        $this->_whereColumn($first, $second, '<=');
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
    public function whereLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
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
    public function whereNotLikeColumn($first, $second): static
    {
        $this->_whereLikeColumn($first, $second, true);
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
    public function whereBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second);
        return $this;
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
            'bool' => $this->getValidatedCondition($boolean),
        ];
    }

    /**
     * @param string $boolean
     * @return string
     */
    protected function getValidatedCondition(string $boolean): string
    {
        return in_array($boolean, ['and', 'or']) ? $boolean : 'and';
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
    public function whereNotBetweenColumn($column, $first, $second): static
    {
        $this->_whereBetweenColumn($column, $first, $second, true);
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

    /**
     * @inheritDoc
     */
    public function reset(): static
    {
        $this->where = [];
        $this->bindings = [];

        return $this;
    }
}
