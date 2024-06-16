<?php

namespace App\Notifications;

use App\Enums\SMS\SMSSenderTypesEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Notifications\Messages\SMSMessage;
use App\Services\Contracts\SmsLogServiceInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Tzsk\Sms\Facades\Sms;

class SMSChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        /**
         * @var SMSMessage $smsObject
         */
        $smsObject = $notification->toSms($notifiable);

        $numbers = Arr::wrap($smsObject->getNumber());

        // Send notification to the $notifiable instance...
        Sms::send($smsObject->getMessage())->to($numbers)->dispatch();

        // log sms in sms_logs table too
        /**
         * @var SmsLogServiceInterface $loggerService
         */
        $loggerService = app()->get(SmsLogServiceInterface::class);

        $panelName = config('sms.default', 'unknown');
        $loggerService->create([
            'receiver_numbers' => implode(', ', $numbers),
            'panel_number' => config('sms.drivers.' . $panelName . '.from'),
            'panel_name' => $panelName,
            'body' => $smsObject->getMessage(),
            'type' => $smsObject->getType()?->value ?? SMSTypesEnum::OTHERS->value,
            'sender' => SMSSenderTypesEnum::SYSTEM->value,
        ]);
    }
}
