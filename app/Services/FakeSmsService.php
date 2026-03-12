<?php

namespace App\Services;

class FakeSmsService
{
    public function send($to, $message)
    {
        // Log the SMS to storage/logs/fake_sms.log for development/testing
        $log = sprintf("[%s] To: %s | Message: %s\n", now(), $to, $message);
        file_put_contents(storage_path('logs/fake_sms.log'), $log, FILE_APPEND);
        return true;
    }
}
