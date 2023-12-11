<?php

namespace App\Services;

use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Model;

class SettingService extends Service implements SettingServiceInterface
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getSetting(string $settingName): ?Model
    {
        if (empty(trim($settingName))) return null;

        $where = new WhereBuilder('settings');
        $where->whereEqual('name', $settingName);

        return $this->repository->findWhere($where->build());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'name' => $attributes['name'],
            'setting_value' => $attributes['setting_value'],
            'group_name' => $attributes['group_name'],
            'min_value' => $attributes['min_value'] ?? '',
            'max_value' => $attributes['max_value'] ?? '',
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
        }
        if (isset($attributes['setting_value'])) {
            $updateAttributes['setting_value'] = $attributes['setting_value'];
        }
        if (isset($attributes['group_name'])) {
            $updateAttributes['group_name'] = $attributes['group_name'];
        }
        if (isset($attributes['min_value'])) {
            $updateAttributes['min_value'] = $attributes['min_value'];
        }
        if (isset($attributes['max_value'])) {
            $updateAttributes['max_value'] = $attributes['max_value'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id, bool $permanent = false): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function batchDeleteByIds(array $ids, bool $permanent = false): bool
    {
        return false;
    }
}
