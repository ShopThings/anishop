<?php

namespace App\Listeners;

use App\Events\PaymentUnverifiedEvent;
use App\Events\PaymentVerifiedEvent;
use App\Notifications\PaymentNotification;
use App\Support\Event\AbstractListener;
use DateTime;

class PaymentEventSubscriber extends AbstractListener
{
    /**
     * @param PaymentVerifiedEvent $event
     * @return bool
     */
    public function handleVerifiedPayment(PaymentVerifiedEvent $event): bool
    {
        $event->user->notify((new PaymentNotification($event->order->code))->afterCommit());
        return true;
    }

    /**
     * @param PaymentUnverifiedEvent $event
     * @return bool
     */
    public function handleUnverifiedPayment(PaymentUnverifiedEvent $event): bool
    {
        $event->user->notify((new PaymentNotification($event->order->code, $event->message))->afterCommit());
        return true;
    }

    public function subscribe(): array
    {
        return [
            PaymentVerifiedEvent::class => 'handleVerifiedPayment',
            PaymentUnverifiedEvent::class => 'handleUnverifiedPayment',
        ];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinutes(5);
    }
}
