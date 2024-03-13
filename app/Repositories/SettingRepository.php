<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Support\Repository;

class SettingRepository extends Repository implements SettingRepositoryInterface
{
    protected bool $useSoftDeletes = false;

    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }
}
