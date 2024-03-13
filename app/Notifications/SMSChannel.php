<?php

namespace App\Notifications;

use App\Notifications\Messages\SMSMessage;
use Illuminate\Notifications\Notification;

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

        // Send notification to the $notifiable instance...
        // ...

        // log sms in sms_logs table too
        // ...
    }
}
