<?php

namespace App\Repositories;

use App\Models\Complaint;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Support\Repository;

class ComplaintRepository extends Repository implements ComplaintRepositoryInterface
{
    public function __construct(Complaint $model)
    {
        parent::__construct($model);
    }
}
