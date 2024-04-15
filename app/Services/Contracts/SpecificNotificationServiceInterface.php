<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use Illuminate\Support\Collection;

interface SpecificNotificationServiceInterface extends ServiceInterface
{
    /**
     * @param AccountTypesEnum[] $accountTypes
     * @param AccountNotificationTypesEnum[] $notificationTypes
     * @return Collection
     */
    public function getAccountsForSpecificTypes(
        array $accountTypes = [],
        array $notificationTypes = []
    ): Collection;
}
