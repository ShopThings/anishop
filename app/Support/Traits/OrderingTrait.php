<?php

namespace App\Support\Traits;

trait OrderingTrait
{
    /**
     * @param array $orders
     * @param int $dept - How many nested array should travel
     * @return array
     */
    protected function convertOrdersColumnToArray(array $orders, int $dept = 512): array
    {
        if ($dept < 0) return $orders;

        $orderArr = [];
        foreach ($orders as $column => $sort) {
            // iterate to flatten nested arrays
            if (is_array($sort))
                $orderArr = $orderArr + $this->convertOrdersColumnToArray($sort, $dept - 1);

            // prevent column duplication
            if (!isset($orderArr[$column]))
                $orderArr[$column] = $sort;
        }
        return $orderArr;
    }
}
