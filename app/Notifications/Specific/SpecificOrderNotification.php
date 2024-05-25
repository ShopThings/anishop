<?php

namespace App\Notifications\Specific;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Models\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SpecificOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected OrderDetail $order
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
            'type' => UserNotificationTypesEnum::ORDER_PLACED->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::ORDER_PLACED->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::HIGH->value,
            'message' => 'سفارش با کد ' .
                '«' . $this->order->code . '»' .
                'برای ' .
                trim($this->order->first_name . ' ' . $this->order->last_name) .
                'ثبت شده است، لطفا بررسی نمایید.',
        ];
    }
}
