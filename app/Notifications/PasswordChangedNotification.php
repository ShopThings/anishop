<?php

namespace App\Notifications;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
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
            'type' => UserNotificationTypesEnum::RECOVER_PASS,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::RECOVER_PASS->value),
            'priority' => UserNotificationPrioritiesEnum::HIGH,
            'message' => 'کلمه عبور شما بازنشانی شد.',
            'created_at' => now(),
        ];
    }
}
