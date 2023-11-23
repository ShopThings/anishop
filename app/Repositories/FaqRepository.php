<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Support\Repository;

class FaqRepository extends Repository implements FaqRepositoryInterface
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }
}
