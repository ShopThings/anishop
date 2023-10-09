<?php

namespace App\Support\Traits;

use App\Traits\VersionTrait;

trait ServiceTrait
{
    use VersionTrait;

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
            if (is_array($sort))
                $orderArr = $orderArr + $this->convertOrdersColumnToArray($sort, $dept - 1);

            if (!isset($orderArr[$column]))
                $orderArr[$column] = $sort;
        }
        return $orderArr;
    }
}
