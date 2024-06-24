<?php

namespace App\Providers;

use App\Events\ComplaintAddedEvent;
use App\Events\ContactAddedEvent;
use App\Events\SettingUpdatedEvent;
use App\Listeners\ComplaintReceivedNotificationListener;
use App\Listeners\ContactReceivedNotificationListener;
use App\Listeners\OrderEventSubscriber;
use App\Listeners\PaymentEventSubscriber;
use App\Listeners\SettingChangedNotificationListener;
use App\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SettingUpdatedEvent::class => [
            SettingChangedNotificationListener::class,
        ],
        ContactAddedEvent::class => [
            ContactReceivedNotificationListener::class,
        ],
        ComplaintAddedEvent::class => [
            ComplaintReceivedNotificationListener::class,
        ],
//        Registered::class => [
//
//        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventSubscriber::class,
        OrderEventSubscriber::class,
        PaymentEventSubscriber::class,
    ];

    /**
     * The model observers to register.
     *
     * @var array<string, array<int, string>>
     */
    protected $observers = [

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
