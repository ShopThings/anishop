<?php

namespace App\Repositories\Contracts;

interface ReportRepositoryInterface
{
    /**
     * @return array
     */
    public function getUsersQueryBuilderInfo(): array;

    /**
     * @return array
     */
    public function getProductsQueryBuilderInfo(): array;

    /**
     * @return array
     */
    public function getOrdersQueryBuilderInfo(): array;
}
