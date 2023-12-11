<?php

namespace App\Listeners;

use App\Events\OrderPlacedEvent;
use App\Events\OrderStatusChangedEvent;
use App\Events\ReturnOrderRequestedEvent;
use App\Events\ReturnOrderStatusChangedEvent;
use App\Support\Event\AbstractListener;
use DateTime;

class OrderEventSubscriber extends AbstractListener
{
    public function handleOrderPlaced(OrderPlacedEvent $event)
    {
        //
    }

    public function handleOrderStatusChanged(OrderStatusChangedEvent $event)
    {
        //
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
