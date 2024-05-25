<?php

namespace App\Notifications;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContactAddedNotification extends Notification
{
    use Queueable;

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
            'type' => UserNotificationTypesEnum::OTHERS->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::OTHERS->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::LOW->value,
            'message' => 'پیام شما ثبت شد.',
        ];
    }
}
