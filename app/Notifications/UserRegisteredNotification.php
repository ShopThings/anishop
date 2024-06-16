<?php

namespace App\Notifications;

use App\Enums\Notification\UserNotificationPrioritiesEnum;
use App\Enums\Notification\UserNotificationTypesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\Messages\SMSMessage;
use App\Services\Contracts\SettingServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var SettingServiceInterface
     */
    protected SettingServiceInterface $settingService;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User         $user,
        protected Setting      $smsSetting,
        protected SMSTypesEnum $smsType
    )
    {
        $this->settingService = app()->make(SettingServiceInterface::class);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', SMSChannel::class];
    }

    public function toSms(object $notifiable): SMSMessage
    {
        $msg = $this->smsSetting->setting_value ?: $this->smsSetting->default_value;

        $titleSetting = $this->settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $mobile = $this->user->username;
        $replacements = [
            'username' => $mobile,
            'shop' => $title,
        ];

        return new SMSMessage(
            $mobile,
            replace_sms_variables($msg, $this->smsType, $replacements),
            $this->smsType
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => UserNotificationTypesEnum::SIGNUP->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::SIGNUP->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::LOW->value,
            'message' => 'خوش آمدید، ثبت نام شما تکمیل شد. از خرید خود لذت ببرید😊',
        ];
    }
}
