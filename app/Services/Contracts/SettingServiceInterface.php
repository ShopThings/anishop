<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use Illuminate\Database\Eloquent\Model;

interface SettingServiceInterface extends ServiceInterface
{
    /**
     * @param string $settingName
     * @return Model|null
     */
    public function getSetting(string $settingName): ?Model;
}
