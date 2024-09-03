<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Request;

class EventService
{
    public static function getEvents($search)
    {
        return Event::filter(['search' => $search])->with("user:id,name")->get()->map(function ($event) {
            $event->created_by = $event->user;
            $event->canTakeActions = $event->canTakeActions();
            $event->is_favourite = $event->isFavourite();
            $event->joiningStatus = $event->joiningStatus();
            return $event;
        });
    }

    public static function makeEvent($request)
    {
        $userId = auth()->id();
        $eventId = $request->id;
        $image = $request->image;

        $imagePath = null;



        if (isset($image)) {
            if ($image) {
                $imagePath = $image->store("images", "public");
                $imagePath = Storage::url($imagePath);
            } else {
                $imagePath = null;
            }
        } else {
            if ($eventId) {
                $event = Event::find($eventId);
                $imagePath = $event->image;
            }
        }

        return Event::updateOrCreate(["id" => $eventId], [
            "created_by" => $userId,
            "name" => $request->name,
            "from" => $request->from,
            "to" => $request->to,
            "description" => $request->description,
            "extra_description" => $request->extra_description,
            "is_public" => $request->isPublic == "true" ? 1 : 0,
            "image" => $imagePath
        ]);
    }

    public static function joinEvent(int $userId, $eventId)
    {
        $user = User::find($userId);

        $event = Event::findOrFail($eventId);

        if (!Gate::allows("joinEvent", $event)) {
            abort(403);
        }

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

    public static function removeUserFromEvent($userId, $eventId)
    {
        $user = User::findOrFail($userId);

        $user->joinedEvents()->detach($eventId);
    }

    public static function removeUser($userId, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!Gate::allows("removeUser", $event)) {
            abort(403);
        }

        return self::removeUserFromEvent($userId, $eventId);
    }

    public static function deleteEvent($eventId, $forceDelete = false)
    {
        $event = Event::findOrFail($eventId);


        if (!Gate::allows("deleteEvent", $event)) {
            abort(403);
        }


        if (!$forceDelete && $event->users->count() > 1) {
            return response()->json([
                "message" => "there are members joined in that event are you sure you want to delete it?"
            ], 400);
        }

        $event->delete();

        return response()->json([
            "message" => "event has been deleted"
        ]);
    }

    public static function exitEvent($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!Gate::allows("exitEvent", $event)) {
            abort(403);
        }

        self::removeUserFromEvent(auth()->id(), $eventId);

        return response()->json([
            'message' => "you have exited the event successfully"
        ]);
    }

    public static function toggleFavourite($eventId)
    {

        $event = Event::findOrFail($eventId);

        if ($event->isFavourite()) {
            auth()->user()->favouriteEvents()->detach($eventId);
            $message = "event has been removed from favourites";

        } else {
            auth()->user()->favouriteEvents()->attach($eventId);
            $message = "event has been added to favourites";
        }

        return response()->json([
            "message" => $message,
            "status" => "success"
        ]);
    }

    public static function requestToJoinEvent($userId, $eventId)
    {

        $user = User::findOrFail($userId);

        if ($user->joinedEvents()->find($eventId)) {
            return response()->json([
                "message" => "you already joined this event"
            ], 400);
        }

        $request = Request::where("user_id", $userId)->where("event_id", $eventId)->where("status", "pending")->first();

        if ($request) {
            return response()->json([
                "message" => "you already requested to join this event"
            ], 400);
        }

        $request = Request::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'status' => "pending",
        ]);

        return response()->json([
            "message" => "your request has been succesfully created"
        ]);
    }

}