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
use Illuminate\Support\Str;

class VerifyCodeNotification extends Notification implements ShouldQueue
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
        protected string       $code,
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

        $titleSetting = $this->settingService->getSetting(SettingsEnum::TITLE->value);
        $title = $titleSetting->setting_value ?: $titleSetting->default_value;

        $mobile = $this->user->username;
        $replacements = [
            'username' => $mobile,
            'first_name' => $this->user->first_name ?: 'کاربر',
            'shop' => $title,
        ];

        return new SMSMessage(
            $mobile,
            replace_sms_variables($msg, $this->smsType, $replacements + ['code' => $this->code]),
            $this->smsType,
            replace_sms_variables(
                $msg,
                $this->smsType,
                $replacements + ['code' => Str::repeat('X', mb_strlen($this->code))]
            ),
        );
    }
}
