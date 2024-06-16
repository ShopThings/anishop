<?php

namespace App\Support\WhereBuilder;

use App\Contracts\BuildExpressionInterface;
use Closure;

interface WhereBuilderInterface extends BuildExpressionInterface
{
    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereEqual($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereNotEqual($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereNotEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereGreaterThan($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereGreaterThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereGreaterThanEqual($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereGreaterThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereLessThan($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereLessThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function whereLessThanEqual($column, $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereLessThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @param string $boolean
     * @param bool $not
     * @return static
     */
    public function whereLike(
        $column,
        $value,
        string $operand = '%{value}%',
        string $boolean = 'and',
        bool $not = false
    ): static;

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @param bool $not
     * @return static
     */
    public function orWhereLike($column, $value, string $operand = '%{value}%', bool $not = false): static;

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @param string $boolean
     * @return static
     */
    public function whereNotLike($column, $value, string $operand = '%{value}%', string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $value
     * @param string $operand
     * @return static
     */
    public function orWhereNotLike($column, $value, string $operand = '%{value}%'): static;

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @param bool $not
     * @return static
     */
    public function whereIn($column, array $value, string $boolean = 'and', bool $not = false): static;

    /**
     * @param $column
     * @param array $value
     * @param bool $not
     * @return static
     */
    public function orWhereIn($column, array $value, bool $not = false): static;

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return static
     */
    public function whereNotIn($column, array $value, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param array $value
     * @return static
     */
    public function orWhereNotIn($column, array $value): static;

    /**
     * @param $column
     * @param string $boolean
     * @param bool $not
     * @return static
     */
    public function whereNull($column, string $boolean = 'and', bool $not = false): static;

    /**
     * @param $column
     * @param bool $not
     * @return static
     */
    public function orWhereNull($column, bool $not = false): static;

    /**
     * @param $column
     * @param string $boolean
     * @return static
     */
    public function whereNotNull($column, string $boolean = 'and'): static;

    /**
     * @param $column
     * @return static
     */
    public function orWhereNotNull($column): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param string $boolean
     * @param bool $not
     * @return static
     */
    public function whereBetween($column, $first, $second, string $boolean = 'and', bool $not = false): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param bool $not
     * @return static
     */
    public function orWhereBetween($column, $first, $second, bool $not = false): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @param string $boolean
     * @return static
     */
    public function whereNotBetween($column, $first, $second, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereNotBetween($column, $first, $second): static;

    /**
     * @param $column
     * @param $pattern
     * @param string $boolean
     * @return static
     */
    public function whereRegexp($column, $pattern, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $pattern
     * @return static
     */
    public function orWhereRegexp($column, $pattern): static;

    /**
     * @param string $expression
     * @param array $bindings
     * @return static
     */
    public function whereRaw(string $expression, array $bindings): static;

    /**
     * @param string $expression
     * @param array $bindings
     * @return static
     */
    public function orWhereRaw(string $expression, array $bindings): static;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @param string $boolean
     * @return static
     */
    public function where($column, $operator = null, $value = null, string $boolean = 'and'): static;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return static
     */
    public function orWhere($column, $operator, $value = null): static;

    /**
     * Send NULL if you don't need any prefix in group
     *
     * @param Closure $callback
     * @param string|null $prefix
     * @return static
     */
    public function group(Closure $callback, ?string $prefix = ''): static;

    /**
     * Send NULL if you don't need any prefix in group
     *
     * @param Closure $callback
     * @param string|null $prefix
     * @return static
     */
    public function orGroup(Closure $callback, ?string $prefix = ''): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereNotEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereNotEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereGreaterThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereGreaterThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereGreaterThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereGreaterThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereLessThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereLessThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereLessThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereLessThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereLikeColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereLikeColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function whereNotLikeColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereNotLikeColumn($first, $second): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function whereBetweenColumn($column, $first, $second): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereBetweenColumn($column, $first, $second): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function whereNotBetweenColumn($column, $first, $second): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereNotBetweenColumn($column, $first, $second): static;

    /**
     * @param $value
     * @param callable|null $callback
     * @param callable|null $default
     * @return mixed
     */
    public function when($value = null, callable $callback = null, callable $default = null);

    /**
     * @param $value
     * @param callable|null $callback
     * @param callable|null $default
     * @return mixed
     */
    public function unless($value = null, callable $callback = null, callable $default = null);

    /**
     * @return static
     */
    public function reset(): static;
}
