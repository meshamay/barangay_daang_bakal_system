<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\TwilioSmsService;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Check if Twilio is properly configured
        if (!config('services.twilio.sid') || !config('services.twilio.token') || !config('services.twilio.from')) {
            \Log::warning('SMS notification skipped: Twilio credentials not configured');
            return;
        }

        if (method_exists($notification, 'toSms')) {
            $message = $notification->toSms($notifiable);

            if ($notifiable->contact_number) {
                try {
                    $twilioService = app(\App\Services\TwilioSmsService::class);
                    $twilioService->send($notifiable->contact_number, $message);
                } catch (\Exception $e) {
                    \Log::error('SMS sending failed: ' . $e->getMessage());
                    // Continue execution - don't break the notification process
                }
            }
        }
    }
}