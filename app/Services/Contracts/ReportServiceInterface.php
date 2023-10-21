<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;

interface ReportServiceInterface extends ServiceInterface
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
