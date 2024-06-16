<?php

namespace App\Notifications\Specific;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SpecificReturnOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User               $user,
        protected ReturnOrderRequest $request
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
            'message' => 'درخواست مرجوع برای سفارش به کد ' .
                '«' . $this->request->order->code . '»' .
                ' با کد مرجوع ' .
                '«' . $this->request->code . '»' .
                'برای ' .
                trim($this->user->first_name . ' ' . $this->user->last_name) .
                'ثبت شده است. ' .
                'به دلیل نهایی شدن تصمیم کاربر، لطفا پس از ۱ ساعت بررسی نهایی نمایید.',
        ];
    }
}
