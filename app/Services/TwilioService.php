<?php

namespace App\Services;

use App\Models\AddMember;
use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->twilio = new Client($sid, $token);
    }

    public function sendSMS(string $to, string $message)
    {
        $this->twilio->messages->create($to, [
            'from' => config('services.twilio.phone_number'),
            'body' => $message
        ]);
    }

    public function sendBulkSMS(array $recipients, string $message)
    {
        $phoneNumbers = $recipients;
        $members = AddMember::whereIn('phone_number', $phoneNumbers)->get()->keyBy('phone_number');

        $messages = collect($phoneNumbers)->map(function ($phoneNumber) use ($members, $message) {
            $name = $members->has($phoneNumber) ? $members[$phoneNumber]->name : null;

            $personalizedMessage = $name ? "Hello {$name}, {$message}" : "Hello, {$message}";

            return [
                'to' => $phoneNumber,
                'body' => $personalizedMessage
            ];
        })->all();

        foreach ($messages as $message) {
            $this->twilio->messages->create($message['to'], [
                'from' => config('services.twilio.phone_number'),
                'body' => $message['body']
            ]);
        }
    }
}
