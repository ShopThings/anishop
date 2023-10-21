<?php

namespace App\Repositories;

use App\Models\ContactUs;
use App\Repositories\Contracts\ContactUsRepositoryInterface;
use App\Support\Repository;

class ContactUsRepository extends Repository implements ContactUsRepositoryInterface
{
    public function __construct(ContactUs $model)
    {
        parent::__construct($model);
    }
}
