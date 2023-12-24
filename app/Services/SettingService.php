<?php

namespace App\Services;

use App\Enums\Settings\SettingGroupsEnum;
use App\Enums\Settings\SettingsEnum;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mews\Purifier\Facades\Purifier;

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
    public function getSettings(): Collection
    {
        $settings = $this->repository->all([
            'name', 'setting_value', 'group_name',
            'default_value', 'min_value', 'max_value',
        ]);
        return $this->_refineSettings($settings);
    }

    /**
     * @inheritDoc
     */
    public function getSettingByGroupName(SettingGroupsEnum $groupName): Collection
    {
        $where = new WhereBuilder('settings');
        $where->whereEqual('group_name', $groupName->value);

        $settings = $this->repository->all([
            'name', 'setting_value', 'group_name',
            'default_value', 'min_value', 'max_value',
        ], $where->build());
        return $this->_refineSettings($settings);
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
     * This <b>MUST</b> be used by high privileged users(because of correct setting_value type!)
     *
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = $this->_getUpdateAttributes($attributes);

        if (isset($attributes['name'])) {
            $updateAttributes['name'] = $attributes['name'];
        }

        if (isset($updateAttributes['setting_value'], $updateAttributes['name'])) {
            $updateAttributes['setting_value'] = $this->_prepareSettingValueToStore(
                $updateAttributes['name'],
                $updateAttributes['setting_value']
            );
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * Regularly it is the one <b>SHOULD</b> use for updating a setting
     *
     * @inheritDoc
     */
    public function updateByName(string $name, array $attributes, bool $returnUpdatedModel = false): Model|bool|null
    {
        $updateAttributes = $this->_getUpdateAttributes($attributes);

        if (isset($updateAttributes['setting_value'])) {
            $updateAttributes['setting_value'] = $this->_prepareSettingValueToStore(
                $name,
                $updateAttributes['setting_value']
            );
        }

        $where = new WhereBuilder('settings');
        $where->whereEqual('name', $name);

        $res = $this->repository->updateWhere(
            data: $updateAttributes,
            where: $where->build()
        );

        if (!$returnUpdatedModel) return !!$res;
        if (!$res) return null;

        return $this->repository->findWhere($where->build());
    }

    /**
     * <b>DO NOT NEED</b> any delete operation
     *
     * @inheritDoc
     */
    public function deleteById($id, bool $permanent = false): bool
    {
        return false;
    }

    /**
     * <b>DO NOT NEED</b> any delete operation
     *
     * @inheritDoc
     */
    public function batchDeleteByIds(array $ids, bool $permanent = false): bool
    {
        return false;
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function _getUpdateAttributes(array $attributes): array
    {
        $updateAttributes = [];

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

        return $updateAttributes;
    }

    /**
     * @param Collection $settings
     * @return Collection
     */
    private function _refineSettings(Collection $settings): Collection
    {
        return $settings->map(function ($item) {
            $value = $item['setting_value'] ?: $item['default_value'];
            return [
                'name' => $item['name'],
                'group_name' => $item['group_name'],
                'value' => $this->_castSettingAccordingToName($item['name'], $value),
                'min_value' => $item['min_value'],
                'max_value' => $item['max_value'],
            ];
        });
    }

    /**
     * @param string $name
     * @param mixed $settingValue
     * @return mixed
     */
    private function _castSettingAccordingToName(string $name, mixed $settingValue): mixed
    {
        return match ($name) {
            SettingsEnum::LAT_LNG->value,
            SettingsEnum::KEYWORDS->value,
            SettingsEnum::PHONES->value => explode(',', $settingValue),

            SettingsEnum::SOCIALS->value,
            SettingsEnum::FOOTER_NAMADS->value => json_decode($settingValue) ?? [],

            default => $settingValue,
        };
    }

    /**
     * @param string $name
     * @param mixed $settingValue
     * @return mixed
     */
    private function _prepareSettingValueToStore(string $name, mixed $settingValue): mixed
    {
        return match ($name) {
            SettingsEnum::DESCRIPTION->value,
            SettingsEnum::SMS_SIGNUP->value,
            SettingsEnum::SMS_ACTIVATION->value,
            SettingsEnum::SMS_RECOVER_PASS->value,
            SettingsEnum::SMS_BUY->value,
            SettingsEnum::SMS_ORDER_STATUS->value,
            SettingsEnum::SMS_RETURN_ORDER->value,
            SettingsEnum::SMS_RETURN_ORDER_STATUS->value,
            SettingsEnum::FOOTER_COPYRIGHT->value,
            SettingsEnum::ADDRESS->value,
            SettingsEnum::FOOTER_DESCRIPTION->value => Purifier::clean($settingValue),

            SettingsEnum::LAT_LNG->value,
            SettingsEnum::KEYWORDS->value,
            SettingsEnum::PHONES->value => is_array($settingValue) ? implode(',', $settingValue) : $settingValue,

            SettingsEnum::STORE_PROVINCE->value,
            SettingsEnum::STORE_CITY->value,
            SettingsEnum::DEFAULT_POST_PRICE->value => intval($settingValue),

            SettingsEnum::PRODUCT_EACH_PAGE->value,
            SettingsEnum::BLOG_EACH_PAGE->value => intval($settingValue) ?? 15,

            SettingsEnum::MIN_FREE_POST_PRICE->value => intval($settingValue) ?? '',

            SettingsEnum::SOCIALS->value,
            SettingsEnum::FOOTER_NAMADS->value => json_encode($this->_refineSocialsAndNamadsKey($settingValue)),

            default => $settingValue,
        };
    }

    /**
     * @param mixed $items
     * @return array|string
     */
    private function _refineSocialsAndNamadsKey(mixed $items): array|string
    {
        if (is_array($items)) {
            for ($i = 0; $i < count($items); $i++) {
                if (isset($items[$i]['id'])) {
                    $items[$i]['id'] = $i + 1;
                }
            }

            return $items;
        } else {
            return '';
        }
    }
}
