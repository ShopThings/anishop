<?php

namespace App\Services;

use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use App\Repositories\Contracts\SpecificNotificationRepositoryInterface;
use App\Services\Contracts\SpecificNotificationServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SpecificNotificationService extends Service implements SpecificNotificationServiceInterface
{
    public function __construct(
        protected SpecificNotificationRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getAccountsForSpecificTypes(
        array $accountTypes = [],
        array $notificationTypes = []
    ): Collection
    {
        if (count($accountTypes)) {
            $accountTypes = array_filter(
                $accountTypes,
                fn($item) => $item instanceof AccountTypesEnum
            );
        }
        if (count($notificationTypes)) {
            $notificationTypes = array_filter(
                $notificationTypes,
                fn($item) => $item instanceof AccountNotificationTypesEnum
            );
        }

        $where = new WhereBuilder();
        $where
            ->when(count($accountTypes), function (WhereBuilderInterface $wh) use ($accountTypes) {
                $wh->whereIn('account_type', array_map(fn($item) => $item->value, $accountTypes));
            })
            ->when(count($notificationTypes), function (WhereBuilderInterface $wh) use ($notificationTypes) {
                $wh->whereIn('notification_type', array_map(fn($item) => $item->value, $notificationTypes));
            });

        return $this->repository->all(where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        if (
            is_null(AccountTypesEnum::tryFrom($attributes['account_type']))
            || is_null(AccountNotificationTypesEnum::tryFrom($attributes['notification_type']))
        ) {
            return null;
        }

        $attrs = [
            'account' => $attributes['account'],
            'account_type' => $attributes['account_type'],
            'notification_type' => $attributes['notification_type'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['account'])) {
            $updateAttributes['account'] = $attributes['account'];
        }
        if (
            isset($attributes['account_type']) &&
            !is_null(AccountTypesEnum::tryFrom($attributes['account_type']))
        ) {
            $updateAttributes['account_type'] = AccountTypesEnum::tryFrom($attributes['account_type']);
        }
        if (
            isset($attributes['notification_type']) &&
            !is_null(AccountNotificationTypesEnum::tryFrom($attributes['notification_type']))
        ) {
            $updateAttributes['notification_type'] = AccountNotificationTypesEnum::tryFrom($attributes['notification_type']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
