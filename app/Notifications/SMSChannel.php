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

        // Send notification to the $notifiable instance(only on production)
        if (app()->isProduction()) {
            Sms::send($smsObject->getMessage())->to($numbers)->dispatch();
        }

        // log sms in sms_logs table too
        /**
         * @var SmsLogServiceInterface $loggerService
         */
        $loggerService = app()->get(SmsLogServiceInterface::class);

        $panelName = config('sms.default', 'unknown');
        $creatingObject = [
            'receiver_numbers' => implode(', ', $numbers),
            'panel_number' => config('sms.drivers.' . $panelName . '.from', 'unknown'),
            'panel_name' => $panelName,
            'body' => app()->isProduction()
                ? ($smsObject->getSecuredMessage() ?: $smsObject->getMessage())
                : $smsObject->getMessage(),
            'type' => $smsObject->getType()?->value ?? SMSTypesEnum::OTHERS->value,
        ];

        if (!in_array($creatingObject['type'], array_map(fn($item) => $item->value, SMSTypesEnum::getUserTypes()))) {
            $creatingObject['created_by'] = null;
            $creatingObject['sender'] = SMSSenderTypesEnum::SYSTEM->value;
        } else {
            $creatingObject['sender'] = SMSSenderTypesEnum::USER->value;
        }

        $loggerService->create($creatingObject);
    }
}
