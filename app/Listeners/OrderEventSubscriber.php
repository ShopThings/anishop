<?php

namespace App\Listeners;

use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Events\OrderPaidEvent;
use App\Events\OrderPlacedEvent;
use App\Events\OrderStatusChangedEvent;
use App\Events\ReturnOrderRequestedEvent;
use App\Events\ReturnOrderStatusChangedEvent;
use App\Mail\OrderPlacedMail;
use App\Models\Setting;
use App\Notifications\OrderNotification;
use App\Notifications\OrderSendStatusNotification;
use App\Notifications\ReturnOrderNotification;
use App\Notifications\ReturnOrderStatusNotification;
use App\Notifications\Specific\SpecificOrderNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\SpecificNotificationServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Event\AbstractListener;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Tzsk\Sms\Facades\Sms;

class OrderEventSubscriber extends AbstractListener
{
    public function __construct(
        protected SettingServiceInterface              $settingService,
        protected SpecificNotificationServiceInterface $specificNotificationService,
        protected UserServiceInterface                 $userService
    )
    {
    }

    /**
     * @param OrderPlacedEvent $event
     * @return bool
     */
    public function handleOrderPlaced(OrderPlacedEvent $event): bool
    {
        $model = $this->settingService->getSetting(SettingsEnum::SMS_BUY->value);

        if (!$model instanceof Setting) return false;

        $event->user
            ->notify(
                (new OrderNotification(
                    $event->user,
                    $event->orderCode,
                    $model,
                    SMSTypesEnum::BUY,
                ))->afterCommit()
            );
        return true;
    }

    public function handleOrderPaid(OrderPaidEvent $event): void
    {
        $numbers = $this->specificNotificationService
            ->getAccountsForSpecificTypes([AccountNotificationTypesEnum::ORDER_PLACED]);

        $titleSetting = $this->settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $numbers->each(function ($number) use ($event, $title) {
            if ($number->account_type === AccountTypesEnum::MOBILE->value) {
                $user = $this->userService->getUserByUsername($number->account);
                if (!is_null($user)) {
                    // this is just database notification
                    $user->notify((new SpecificOrderNotification($event->order))->afterCommit());
                }

                // but always send sms either way
                $msg = 'سفارش با کد ' .
                    '«' . $event->order->code . '»' .
                    'برای ' .
                    trim($event->order->first_name . ' ' . $event->order->last_name) .
                    'ثبت شده است، لطفا بررسی نمایید.' . "\n" .
                    $title;
                Sms::send($msg)->to([$number->account])->dispatch();
            } elseif ($number->account_type === AccountTypesEnum::EMAIL->value) {
                Mail::to($number->account)->send(new OrderPlacedMail($event->order, $title));
            }
        });
    }

    /**
     * @param OrderStatusChangedEvent $event
     * @return bool
     */
    public function handleOrderStatusChanged(OrderStatusChangedEvent $event): bool
    {
        $model = $this->settingService->getSetting(SettingsEnum::SMS_ORDER_STATUS->value);

        if (!$model instanceof Setting) return false;

        $event->user
            ->notify(
                (new OrderSendStatusNotification(
                    $event->user,
                    $event->orderCode,
                    $event->sendStatus,
                    $model,
                    SMSTypesEnum::ORDER_STATUS,
                ))->afterCommit()
            );
        return true;
    }

    /**
     * @param ReturnOrderRequestedEvent $event
     * @return bool
     */
    public function handleReturnOrderRequested(ReturnOrderRequestedEvent $event): bool
    {
        $model = $this->settingService->getSetting(SettingsEnum::SMS_RETURN_ORDER->value);

        if (!$model instanceof Setting) return false;

        $event->user
            ->notify(
                (new ReturnOrderNotification(
                    $event->user,
                    $event->returnCode,
                    $event->orderCode,
                    $model,
                    SMSTypesEnum::RETURN_ORDER,
                ))->afterCommit()
            );
        return true;
    }

    /**
     * @param ReturnOrderStatusChangedEvent $event
     * @return bool
     */
    public function handleReturnOrderStatusChange(ReturnOrderStatusChangedEvent $event): bool
    {
        $model = $this->settingService->getSetting(SettingsEnum::SMS_RETURN_ORDER_STATUS->value);

        if (!$model instanceof Setting) return false;

        $event->user
            ->notify(
                (new ReturnOrderStatusNotification(
                    $event->user,
                    $event->returnCode,
                    $event->orderCode,
                    $event->returnStatus,
                    $model,
                    SMSTypesEnum::ORDER_STATUS,
                ))->afterCommit()
            );
        return true;
    }

    public function subscribe(): array
    {
        return [
            OrderPlacedEvent::class => 'handleOrderPlaced',
            OrderPaidEvent::class => 'handleOrderPaid',
            OrderStatusChangedEvent::class => 'handleOrderStatusChanged',
            ReturnOrderRequestedEvent::class => 'handleReturnOrderRequested',
            ReturnOrderStatusChangedEvent::class => 'handleReturnOrderStatusChange',
        ];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(5);
    }
}
