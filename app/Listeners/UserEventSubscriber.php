<?php

namespace App\Listeners;

use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Events\ForgetPasswordEvent;
use App\Events\RegisteredEvent;
use App\Models\Setting;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\UserRegisteredNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Event\AbstractListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class UserEventSubscriber extends AbstractListener
{
    public $tries = 5;

    public function __construct(
        protected SettingServiceInterface $settingService
    )
    {
    }

    public function handleUserLogIn(Login $event)
    {
        // nothing for now
    }

    public function handleUserLogOut(Logout $event)
    {
        // nothing for now
    }

    public function handleUserRegistered(RegisteredEvent $event): bool
    {
        $model = $this->settingService->getSetting(SettingsEnum::SMS_SIGNUP->value);

        if (!$model instanceof Setting) return false;

        $event->user->notify((new UserRegisteredNotification($event->user, $model, SMSTypesEnum::SIGNUP))->afterCommit());
        return true;
    }

    public function handlePasswordForgotten(ForgetPasswordEvent $event): void
    {
        $event->user->notify((new PasswordChangedNotification())->afterCommit());
    }

    public function subscribe(): array
    {
        return [
//            Login::class => 'handleUserLogIn',
//            Logout::class => 'handleUserLogOut',
            RegisteredEvent::class => 'handleUserRegistered',
            ForgetPasswordEvent::class => 'handlePasswordForgotten',
        ];
    }
}
