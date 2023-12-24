<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\Settings\SettingGroupsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SettingServiceInterface extends ServiceInterface
{
    /**
     * @return Collection
     */
    public function getSettings(): Collection;

    /**
     * @param SettingGroupsEnum $groupName
     * @return Collection
     */
    public function getSettingByGroupName(SettingGroupsEnum $groupName): Collection;

    /**
     * @param string $settingName
     * @return Model|null
     */
    public function getSetting(string $settingName): ?Model;

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $returnUpdatedModel
     * @return Model|bool|null
     */
    public function updateByName(string $name, array $attributes, bool $returnUpdatedModel = false): Model|bool|null;
}
