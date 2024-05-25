<?php

namespace App\Support\QB;

use App\Support\Helper\QBHelper;
use App\Support\QB\ItemActions\BetweenItemAction;
use App\Support\QB\ItemActions\ComparisonItemAction;
use App\Support\QB\ItemActions\HasReplacementItemAction;
use App\Support\QB\ItemActions\IsMultipleItemAction;
use App\Support\QB\ItemActions\NullableItemAction;
use App\Support\WhereBuilder\GetterExpressionInterface;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Builder;

trait ReportQueryAppenderTrait
{
    use ReportQueryTaskTrait;

    /**
     * @param Builder $query
     * @param array $appendingQuery
     * @return Builder
     */
    protected function addToEloquentBuilder(Builder $query, array $appendingQuery): Builder
    {
        [$specialQuery, $otherQuery] = $this->separateAppendingQuery($appendingQuery);

        if (count($specialQuery)) {
            $query = $this->addSpecialReportQueryToQuery($query, $specialQuery);
        }

        if (count($otherQuery)) {
            $where = $this->convertReportQueryToWhereClause($otherQuery);

            $query->when(!empty($where->getStatement()), function ($q) use ($where) {
                $q->whereRaw($where->getStatement(), $where->getBindings());
            });
        }

        return $query;
    }

    /**
     * @param array $appendingQuery
     * @return array
     */
    private function separateAppendingQuery(array $appendingQuery): array
    {
        $specialQuery = [];
        $otherQuery = [];

        foreach ($appendingQuery as $item) {
            if (isset($item['children'])) {
                [$tmpSpecialQuery, $tmpOtherQuery] = $this->separateAppendingQuery($item['children']);

                $specialQuery = array_merge($specialQuery, $tmpSpecialQuery);
                $otherQuery = array_merge($otherQuery, $tmpOtherQuery);

            } else {

                if (in_array($item['column'], $this->getSpecialReportColumns())) {
                    $specialQuery[] = $item;
                } else {
                    $otherQuery[] = $item;
                }
            }
        }

        return compact('specialQuery', 'otherQuery');
    }

    /**
     * @return array
     */
    protected function getSpecialReportColumns(): array
    {
        return [];
    }

    /**
     * @param Builder $query
     * @param array $specialQuery
     * @return Builder
     */
    protected function addSpecialReportQueryToQuery(Builder $query, array $specialQuery): Builder
    {
        foreach ($specialQuery as $item) {
            if (isset($item['children'])) {
                $query = $this->addSpecialReportQueryToQuery($query, $item['children']);
            } else {
                $query = $this->defaultSpecialReportQuery($query, $item);
                $query = $this->specialReportQuery($query, $item);
            }
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $item
     * @return Builder
     */
    protected function defaultSpecialReportQuery(Builder $query, array $item): Builder
    {
        $actions = new QueryItemActions([
            new IsMultipleItemAction(function (
                array  $queryItem,
                string $columnName,
                array  $values,
                string $condition,
                bool   $reverseOperation
            ) use (&$query) {

                if (in_array($columnName, $this->getIsMultipleColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);

                    $query = $this->handleSpecialIsMultiple(
                        $query,
                        $column,
                        $values,
                        $condition,
                        $reverseOperation
                    );
                }

            }),

            new HasReplacementItemAction(function (
                array  $queryItem,
                string $columnName,
                       $value,
                string $replacement,
                string $condition,
                bool   $reverseOperation
            ) use (&$query) {

                if (in_array($columnName, $this->getHasReplacementColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);

                    $query = $this->handleSpecialHasReplacement(
                        $query,
                        $column,
                        $value,
                        $replacement,
                        $condition,
                        $reverseOperation
                    );
                }

            }),

            new ComparisonItemAction(function (
                array  $queryItem,
                string $columnName,
                string $operationStatement,
                       $value,
                string $condition
            ) use (&$query) {

                if (in_array($columnName, $this->getSpecialBooleanColumns())) {
                    $columnName = $this->getActualReportColumnFor($columnName);
                    $query = $this->handleSpecialBoolean($query, $queryItem, $columnName, $value, $condition);
                }

                if (in_array($columnName, $this->getGeneralBooleanColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);
                    $query = $this->handleGeneralBoolean($query, $column, $operationStatement, $value, $condition);
                }

                if (in_array($columnName, $this->getComparisonColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);

                    $query = $this->handleSpecialComparison(
                        $query,
                        $column,
                        $operationStatement,
                        $value,
                        $condition
                    );
                }
            }),

            new BetweenItemAction(function (
                array  $queryItem,
                string $columnName,
                       $firstValue,
                       $secondValue,
                string $condition,
                bool   $reverseOperation
            ) use (&$query) {

                if (in_array($columnName, $this->getBetweenColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);

                    $query = $this->handleSpecialBetween(
                        $query,
                        $column,
                        $firstValue,
                        $secondValue,
                        $condition,
                        $reverseOperation
                    );
                }

            }),

            new NullableItemAction(function (
                array  $queryItem,
                string $columnName,
                string $condition,
                bool   $reverseOperation
            ) use (&$query) {

                if (in_array($columnName, $this->getNullableColumns())) {
                    $column = $this->getActualReportColumnFor($columnName);

                    $query = $this->handleSpecialNullable(
                        $query,
                        $column,
                        $condition,
                        $reverseOperation
                    );
                }

            }),
        ]);

        QBHelper::queryItemAction($actions, $item);

        return $query;
    }

    /**
     * @return array
     */
    protected function getIsMultipleColumns(): array
    {
        return [];
    }

    /**
     * @param $column
     * @return array|string|null
     */
    protected function getActualReportColumnFor($column): array|string|null
    {
        if (isset($this->getMappedReportColumnToActualColumn()[$column])) {
            return $this->getMappedReportColumnToActualColumn()[$column];
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getMappedReportColumnToActualColumn(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getHasReplacementColumns(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Add report query to builder operations
    |--------------------------------------------------------------------------
    |
    | A set of methods that can convert a valid report query to a valid where
    | that can be added to a laravel builder as where clause
    |
    */

    /**
     * @return array
     */
    protected function getSpecialBooleanColumns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getGeneralBooleanColumns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getComparisonColumns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getBetweenColumns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getNullableColumns(): array
    {
        return [];
    }

    /**
     * @param Builder $query
     * @param array $item
     * @return Builder
     */
    protected function specialReportQuery(Builder $query, array $item): Builder
    {
        // Overwrite this method if needed
        return $query;
    }

    /**
     * @param $query
     * @return GetterExpressionInterface
     */
    protected function convertReportQueryToWhereClause($query): GetterExpressionInterface
    {
        $where = new WhereBuilder();

        /**
         * $item has the following structure:
         *   [
         *     column => string,
         *     type => string,
         *     operator => array
         *                 [
         *                   value => string,
         *                   statement => string,
         *                   replacement => string, // {value}
         *                   multiple => boolean,
         *                   applyTo => array, // array of QB types
         *                 ],
         *     condition => string,
         *     value => mixed, (optional in some cases)
         *     value2 => mixed, // (optional)
         *   ],
         *   ...,
         */
        foreach ($query as &$item) {
            if (isset($item['children'])) {
                if ($item['condition'] === 'or') {
                    $where->orGroup(function (WhereBuilder &$whereBuilder) use ($item) {
                        $whereBuilder = $this->convertReportQueryToWhereClause($item['children']);
                    });
                } else {
                    $where->group(function (WhereBuilder &$whereBuilder) use ($item) {
                        $whereBuilder = $this->convertReportQueryToWhereClause($item['children']);
                    });
                }
            } else {
                // change frontend column to database column if existed
                $column = $this->getActualReportColumnFor($item['column']);

                if (is_string($column)) {
                    $item['column'] = $column;
                    QBHelper::addToWhereClause($where, $item);
                }
            }
        }

        return $where->build();
    }
}
