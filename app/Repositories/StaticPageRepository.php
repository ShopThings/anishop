<?php

namespace App\Repositories;

use App\Models\StaticPage;
use App\Repositories\Contracts\StaticPageRepositoryInterface;
use App\Support\Repository;

class StaticPageRepository extends Repository implements StaticPageRepositoryInterface
{
    public function __construct(StaticPage $model)
    {
        parent::__construct($model);
    }
}
