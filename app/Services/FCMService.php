<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification(array $deviceTokens, $title, $body)
    {
        $notification = Notification::create($title, $body);
        $messages = array_map(function ($token) use ($notification) {
            return CloudMessage::withTarget('token', $token)
                ->withNotification($notification);
        }, $deviceTokens);

        try {
            $this->messaging->sendAll($messages);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
