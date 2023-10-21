<?php

namespace App\Repositories;

use App\Models\Newsletter;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Support\Repository;

class NewsletterRepository extends Repository implements NewsletterRepositoryInterface
{
    public function __construct(Newsletter $model)
    {
        parent::__construct($model);
    }
}
