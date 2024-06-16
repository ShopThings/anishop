<?php

namespace App\Listeners;

use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use App\Events\SettingUpdatedEvent;
use App\Notifications\Specific\SettingChangedNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\SpecificNotificationServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Event\AbstractListener;

class SettingChangedNotificationListener extends AbstractListener
{
    public $tries = 2;

    public function __construct(
        protected SpecificNotificationServiceInterface $specificNotificationService,
        protected UserServiceInterface                 $userService,
        protected SettingServiceInterface              $settingService
    )
    {
    }

    /**
     * Handle the event.
     */
    public function handle(SettingUpdatedEvent $event): void
    {
        $numbers = $this->specificNotificationService
            ->getAccountsForSpecificTypes(
                [AccountTypesEnum::MOBILE],
                [AccountNotificationTypesEnum::SETTING_UPDATED]
            );

        $numbers->each(function ($number) use ($event) {
            $user = $this->userService->getUserByUsername($number->account);

            if (!is_null($user)) {
                $user->notify(
                    (new SettingChangedNotification(
                        $event->user,
                        $event->settingTitle
                    ))->afterCommit()
                );
            }
        });
    }
}
