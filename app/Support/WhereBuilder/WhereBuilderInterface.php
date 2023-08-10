<?php

namespace App\Support\WhereBuilder;

use App\Contracts\BuildExpressionInterface;
use Closure;

interface WhereBuilderInterface extends BuildExpressionInterface
{
    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereNotEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereGreaterThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereGreaterThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereLessThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereLessThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereLike($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function whereNotLike($column, $value): static;

    /**
     * @param $column
     * @param array $value
     * @return static
     */
    public function whereIn($column, array $value): static;

    /**
     * @param $column
     * @param array $value
     * @return static
     */
    public function whereNotIn($column, array $value): static;

    /**
     * @param $column
     * @return static
     */
    public function whereNull($column): static;

    /**
     * @param $column
     * @return static
     */
    public function whereNotNull($column): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function whereBetween($column, $first, $second): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function whereNotBetween($column, $first, $second): static;

    /**
     * @param $column
     * @param $pattern
     * @return static
     */
    public function whereRegexp($column, $pattern): static;

    /**
     * Send NULL if you don't need any prefix in group
     *
     * @param Closure $callback
     * @param string|null $prefix
     * @return static
     */
    public function group(Closure $callback, ?string $prefix = ''): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereNotEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereGreaterThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereGreaterThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereLessThan($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereLessThanEqual($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereLike($column, $value): static;

    /**
     * @param $column
     * @param $value
     * @return static
     */
    public function orWhereNotLike($column, $value): static;

    /**
     * @param $column
     * @param array $value
     * @return static
     */
    public function orWhereIn($column, array $value): static;

    /**
     * @param $column
     * @param array $value
     * @return static
     */
    public function orWhereNotIn($column, array $value): static;

    /**
     * @param $column
     * @return static
     */
    public function orWhereNull($column): static;

    /**
     * @param $column
     * @return static
     */
    public function orWhereNotNull($column): static;

    /**
     * @param $column
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereBetween($column, $first, $second): static;

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
     * @return $this
     */
    public function orWhereRegexp($column, $pattern): static;

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
    public function whereNotEqualColumn($first, $second): static;

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
    public function whereGreaterThanEqualColumn($first, $second): static;

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
    public function whereLessThanEqualColumn($first, $second): static;

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
    public function whereNotLikeColumn($first, $second): static;

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
    public function whereNotBetweenColumn($column, $first, $second): static;

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
    public function orWhereNotEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereGreaterThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return $this
     */
    public function orWhereGreaterThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return static
     */
    public function orWhereLessThanColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return $this
     */
    public function orWhereLessThanEqualColumn($first, $second): static;

    /**
     * @param $first
     * @param $second
     * @return $this
     */
    public function orWhereLikeColumn($first, $second): static;

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
    public function orWhereBetweenColumn($column, $first, $second): static;

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
}
