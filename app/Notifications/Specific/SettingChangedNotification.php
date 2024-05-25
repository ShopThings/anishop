<?php

namespace App\Notifications\Specific;

use App\Enums\Gates\RolesEnum;
use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SettingChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User   $user,
        protected string $settingTitle,
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
        $roles = RolesEnum::getTranslations($this->user->getRoleNames()->toArray());
        $roles = array_values($roles);

        return [
            'type' => UserNotificationTypesEnum::SETTING_CHANGED->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::SETTING_CHANGED->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::HIGH->value,
            'message' =>
                (
                (!empty($this->user->first_name) || !empty($this->user->last_name))
                    ? trim($this->user->first_name . ' ' . $this->user->last_name)
                    : 'کاربر'
                ) .
                ' با نام کاربری ' .
                $this->user->username .
                ' و نقش(های) ' .
                '«' . implode(',', $roles) . '»' .
                ' تنظیمات با عنوان ' .
                '«' . $this->settingTitle . '»' .
                ' را تغییر داد.',
        ];
    }
}
