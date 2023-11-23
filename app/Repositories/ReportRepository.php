<?php

namespace App\Repositories;

use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getUsersQueryBuilderInfo(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getProductsQueryBuilderInfo(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getOrdersQueryBuilderInfo(): array
    {
        return [];
    }
}
