<?php

namespace App\Repositories;

use App\Models\Color;
use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Support\Repository;

class ColorRepository extends Repository implements ColorRepositoryInterface
{
    public function __construct(Color $model)
    {
        parent::__construct($model);
    }
}
