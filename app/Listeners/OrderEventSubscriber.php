<?php

namespace App\Listeners;

use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Events\OrderPlacedEvent;
use App\Events\OrderStatusChangedEvent;
use App\Events\ReturnOrderRequestedEvent;
use App\Events\ReturnOrderStatusChangedEvent;
use App\Models\Setting;
use App\Notifications\OrderSendStatusNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Event\AbstractListener;
use DateTime;

class OrderEventSubscriber extends AbstractListener
{
    public function __construct(
        protected SettingServiceInterface $settingService
    )
    {
    }

    public function handleOrderPlaced(OrderPlacedEvent $event)
    {
        //
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

    public function handleReturnOrderStatusChange(ReturnOrderStatusChangedEvent $event)
    {
        //
    }

    public function handleReturnOrderRequested(ReturnOrderRequestedEvent $event)
    {
        //
    }

    public function subscribe(): array
    {
        return [
            OrderPlacedEvent::class => 'handleOrderPlaced',
            OrderStatusChangedEvent::class => 'handleOrderStatusChanged',
            ReturnOrderStatusChangedEvent::class => 'handleReturnOrderStatusChange',
            ReturnOrderRequestedEvent::class => 'handleReturnOrderRequested',
        ];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(5);
    }
}
