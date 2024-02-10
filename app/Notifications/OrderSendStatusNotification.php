<?php

namespace App\Notifications;

use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\Messages\SMSMessage;
use App\Services\Contracts\SettingServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use function App\Support\Helper\replace_sms_variables;

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
        return [SMSChannel::class];
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
}
