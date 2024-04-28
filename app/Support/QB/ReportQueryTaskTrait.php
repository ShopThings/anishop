<?php

namespace App\Support\QB;

use App\Enums\DatabaseEnum;
use Illuminate\Database\Eloquent\Builder;

trait ReportQueryTaskTrait
{
    /**
     * @param Builder $query
     * @param $column
     * @param string $operationStatement
     * @param $value
     * @param string $condition
     * @return Builder
     */
    protected function handleGeneralBoolean(
        Builder $query,
                $column,
        string  $operationStatement,
                $value,
        string  $condition
    ): Builder
    {
        if (is_string($column)) {
            $query->where(
                $column,
                $operationStatement,
                !!$value ? DatabaseEnum::DB_YES : DatabaseEnum::DB_NO,
                $condition
            );
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $condition, $operationStatement, $value
            ) {
                $q->where(
                    $columnName,
                    $operationStatement,
                    !!$value ? DatabaseEnum::DB_YES : DatabaseEnum::DB_NO,
                    $condition
                );
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $queryItem
     * @param $column
     * @param $value
     * @param $condition
     * @return Builder
     */
    protected function handleSpecialBoolean(
        Builder $query,
        array   $queryItem,
                $column,
                $value,
                $condition
    ): Builder
    {
        if (is_string($column)) {
            if ($queryItem['operator']['value'] == 'equal') {
                $query->whereNull($column, $condition, !(!!$value));
            } elseif ($queryItem['operator']['value'] == 'notEqual') {
                $query->whereNull($column, $condition, !!$value);
            }
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $column
     * @param array $values
     * @param string $condition
     * @param bool $reverseOperation
     * @return Builder
     */
    protected function handleSpecialIsMultiple(
        Builder $query,
                $column,
        array   $values,
        string  $condition,
        bool    $reverseOperation
    ): Builder
    {
        if (is_string($column)) {
            $query->whereIn($column, $values, $condition, $reverseOperation);
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $condition, $values, $reverseOperation
            ) {
                $q->whereIn($columnName, $values, $condition, $reverseOperation);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $column
     * @param $value
     * @param string $replacement
     * @param string $condition
     * @param bool $reverseOperation
     * @return Builder
     */
    protected function handleSpecialHasReplacement(
        Builder $query,
                $column,
                $value,
        string  $replacement,
        string  $condition,
        bool    $reverseOperation
    ): Builder
    {
        $value = str_replace('{value}', $value, $replacement);
        $operation = $value === ''
            ? ($reverseOperation ? '<>' : '=')
            : ($reverseOperation ? 'not like' : 'like');

        if (is_string($column)) {
            $query->where($column, $operation, $value, $condition);
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $condition, $value, $operation
            ) {
                $q->where($columnName, $operation, $value, $condition);
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $column
     * @param string $operationStatement
     * @param $value
     * @param string $condition
     * @return Builder
     */
    protected function handleSpecialComparison(
        Builder $query,
                $column,
        string  $operationStatement,
                $value,
        string  $condition
    ): Builder
    {
        if (is_string($column)) {
            $query->where($column, $operationStatement, $value, $condition);
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $condition, $operationStatement, $value
            ) {
                // nesting where is to fix 'and'/'or' statement conjunct with sub query
                $q->where(function ($q) use (
                    $columnName, $condition, $operationStatement, $value
                ) {
                    $q->where($columnName, $operationStatement, $value, $condition);
                });
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $column
     * @param $firstValue
     * @param $secondValue
     * @param string $condition
     * @param bool $reverseOperation
     * @return Builder
     */
    protected function handleSpecialBetween(
        Builder $query,
                $column,
                $firstValue,
                $secondValue,
        string  $condition,
        bool    $reverseOperation
    ): Builder
    {
        if (is_string($column)) {
            $query->whereBetween($column, [$firstValue, $secondValue], $condition, $reverseOperation);
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $firstValue, $secondValue, $condition, $reverseOperation
            ) {
                $q->where(function ($q) use (
                    $columnName, $firstValue, $secondValue, $condition, $reverseOperation
                ) {
                    $q->whereBetween($columnName, [$firstValue, $secondValue], $condition, $reverseOperation);
                });
            });
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param $column
     * @param string $condition
     * @param bool $reverseOperation
     * @return Builder
     */
    protected function handleSpecialNullable(
        Builder $query,
                $column,
        string  $condition,
        bool    $reverseOperation
    ): Builder
    {
        if (is_string($column)) {
            $query->whereNull($column, $condition, $reverseOperation);
        } elseif (is_array($column)) {
            $columnName = $column['column'];

            $method = 'whereHas';
            if ($condition === 'or') {
                $method = 'orWhereHas';
            }

            $query->{$method}($column['with'], function ($q) use (
                $columnName, $condition, $reverseOperation
            ) {
                $q->where(function ($q) use (
                    $columnName, $condition, $reverseOperation
                ) {
                    $q->whereNull($columnName, $condition, $reverseOperation);
                });
            });
        }

        return $query;
    }
}
