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

class OrderSendStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected User         $user,
        protected string       $orderCode,
        protected string       $sendStatus,
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

        $titleSetting = $this->settingService->getSetting(SettingsEnum::SMS_ORDER_STATUS->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $mobile = $this->user->username;
        $replacements = [
            'username' => $mobile,
            'shop' => $title,
            'order_code' => $this->orderCode,
            'status' => $this->sendStatus,
        ];

        if (!empty($this->user->first_name)) {
            $replacements['first_name'] = $this->user->first_name;
        }

        return new SMSMessage(
            $mobile,
            replace_sms_variables($msg, $this->smsType, $replacements)
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
            'type' => UserNotificationTypesEnum::ORDER_STATUS->value,
            'type_value' => UserNotificationTypesEnum::getTranslations(UserNotificationTypesEnum::ORDER_STATUS->value, 'نامشخص'),
            'priority' => UserNotificationPrioritiesEnum::HIGH->value,
            'message' => 'سفارش با کد' .
                ' <span>' . $this->orderCode . '</span> ' .
                'به وضعیت' .
                ' <strong>' . $this->sendStatus . '</strong> ' .
                'تغییر یافت.',
        ];
    }
}
