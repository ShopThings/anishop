<?php

namespace App\Notifications;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string  $orderCode,
        protected ?string $failedMessage = null
    )
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => UserNotificationTypesEnum::PAYMENT->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::PAYMENT->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::NORMAL->value,
            'message' => 'پرداخت برای سفارش با کد' .
            ' <span>' . $this->orderCode . '</span> ' .
            empty($this->failedMessage) ? 'با موفقیت انجام شد.' : 'انجام نشد.' . ' "' . $this->failedMessage . '"',
        ];
    }
}
