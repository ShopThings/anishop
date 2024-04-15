<?php

namespace App\Notifications;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected User   $user,
        protected string $path
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
        if (str_contains($this->path, 'users')) {
            $msg = ' از کاربران ';
        } elseif (str_contains($this->path, 'products')) {
            $msg = ' از محصولات ';
        } elseif (str_contains($this->path, 'orders')) {
            $msg = ' از سفارشات ';
        }

        return [
            'type' => UserNotificationTypesEnum::EXPORT->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::EXPORT->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::HIGH->value,
            'message' => 'گرفتن خروجی' . $msg . 'و ذخیره در مسیر ' . '[' . $this->path . ']',
        ];
    }
}
