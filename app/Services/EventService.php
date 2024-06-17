<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;

class EventService {
    public static function getEvents() {
        return Event::all();
    }

    public static function makeEvent($userId, $name, $from, $to) {
        return Event::create([
            "created_by" => $userId,
            "name" => $name,
            "from" => $from,
            "to" => $to,
        ]);
    }

    public static function joinEvent($userId, $eventId) {
        $user = User::find($userId);

        $event = Event::find($eventId);

        if (!$event) {
            return response()->json([
                "message"=> "this event does not exist"
            ], 400);

        } else {
            $alreadyJoined = $user->joinedEvents()->find($eventId);

            if ($alreadyJoined) {
                return response()->json([
                    "message" => "you've already joined in this event"
                ], 400);
            } else {
                $user->joinedEvents()->attach($eventId);

                return response()->json([
                    "message" => "you've successfully joined in this event"
                ]);
            }
        }

    }
}