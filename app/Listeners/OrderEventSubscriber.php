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
use App\Mail\ReturnOrderRequestedMail;
use App\Models\Setting;
use App\Notifications\OrderNotification;
use App\Notifications\OrderSendStatusNotification;
use App\Notifications\ReturnOrderNotification;
use App\Notifications\ReturnOrderStatusNotification;
use App\Notifications\Specific\SpecificOrderNotification;
use App\Notifications\Specific\SpecificReturnOrderNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\SpecificNotificationServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Event\AbstractListener;
use DateTime;
use Exception;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    /**
     * @param OrderPaidEvent $event
     * @return void
     */
    public function handleOrderPaid(OrderPaidEvent $event): void
    {
        $numbers = $this->specificNotificationService
            ->getAccountsForSpecificTypes([AccountNotificationTypesEnum::ORDER_PLACED]);

        $titleSetting = $this->settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $msg = 'سفارش با کد ' .
            '«' . $event->order->code . '»' .
            'برای ' .
            trim($event->order->first_name . ' ' . $event->order->last_name) .
            'ثبت شده است، لطفا بررسی نمایید.' . "\n" .
            $title;

        /*
        |--------------------------------------------------------------------------
        | If you need to send parameter as an array instead of model,
        | use below snippet.
        |--------------------------------------------------------------------------
        |
        | $order = $event->order->only(['code', 'first_name', 'ordered_at']);
        | $order['items'] = [];
        | foreach ($event->order?->items ?? [] as $item) {
        |     $order['items'][] = $item->only(['product_title', 'quantity']);
        | }
        |
        */

        $this->sendToSpecificUsers(
            numbers: $numbers,
            notification: (new SpecificOrderNotification($event->order))->afterCommit(),
            msg: $msg,
            mailable: new OrderPlacedMail($event->order, $title)
        );
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
        $numbers = $this->specificNotificationService
            ->getAccountsForSpecificTypes([AccountNotificationTypesEnum::RETURN_ORDER_REQUESTED]);

        $titleSetting = $this->settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $msg = 'درخواست مرجوع برای سفارش به کد ' .
            '«' . $event->request->order->code . '»' .
            ' با کد مرجوع ' .
            '«' . $event->request->code . '»' .
            'برای ' .
            trim($event->user->first_name . ' ' . $event->user->last_name) .
            'ثبت شده است. ' .
            'به دلیل نهایی شدن تصمیم کاربر، لطفا پس از ۱ ساعت بررسی نهایی نمایید.' . "\n" .
            $title;

        /*
        |--------------------------------------------------------------------------
        | If you need to send parameter as an array instead of model,
        | use below snippet.
        |--------------------------------------------------------------------------
        |
        | $request = $event->request->only(['code', 'requested_at']);
        | $request['order'] = $event->request->order->code;
        | $request['user'] = $event->request->user->first_name;
        |
        | $request['return_order_items'] = [];
        | foreach ($event->request?->return_order_items ?? [] as $item) {
        |     $request['return_order_items'] = [
        |         'quantity' => $item->quantity,
        |         'order_item' => $item->orderItem->only(['product_title', 'quantity'])
        |     ];
        | }
        |
        */

        $this->sendToSpecificUsers(
            numbers: $numbers,
            notification: (new SpecificReturnOrderNotification(
                $event->user,
                $event->request
            ))->afterCommit(),
            msg: $msg,
            mailable: new ReturnOrderRequestedMail($event->request, $title)
        );

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

    /**
     * @param Collection $numbers
     * @param Notification $notification
     * @param string $msg
     * @param Mailable $mailable
     * @return void
     */
    private function sendToSpecificUsers(
        Collection   $numbers,
        Notification $notification,
        string       $msg,
        Mailable     $mailable
    ): void
    {
        $numbers->each(function ($number) use ($notification, $msg, $mailable) {
            if ($number->account_type === AccountTypesEnum::MOBILE->value) {

                $user = $this->userService->getUserByUsername($number->account);
                if (!is_null($user)) {
                    $user->notify($notification);
                }

                Sms::send($msg)->to([$number->account])->dispatch();

            } elseif ($number->account_type === AccountTypesEnum::EMAIL->value) {

                try {
                    Mail::to($number->account)->send($mailable);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        });
    }
}
