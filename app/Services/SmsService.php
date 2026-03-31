<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsService
{
    public function send(string $phone, string $message): bool
    {
        $sid    = config('services.twilio.sid');
        $token  = config('services.twilio.token');
        $from   = config('services.twilio.number');

        if (empty($sid) || empty($token) || empty($from)) {
            Log::info('SmsService: Twilio credentials not configured. SMS not sent.', [
                'phone' => $phone, 'message' => $message,
            ]);
            return false;
        }

        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . preg_replace('/^0/', '', trim($phone));
        }

        try {
            $client = new Client($sid, $token);
            $client->messages->create($phone, [
                'from' => $from,
                'body' => $message,
            ]);
            Log::info('SmsService: SMS sent via Twilio.', ['phone' => $phone]);
            return true;
        } catch (\Throwable $e) {
            Log::error('SmsService: Twilio exception.', ['error' => $e->getMessage(), 'phone' => $phone]);
            return false;
        }
    }
}
