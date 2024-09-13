<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeEventValidator;
use App\Models\Event;
use App\Models\User;
use App\Services\EventService;
use Illuminate\Http\Request;


class EventController extends Controller
{

    public function events(Request $request)
    {
        return EventService::getEvents($request->search);
    }


    public function makeEvent(MakeEventValidator $request)
    {
        return EventService::makeEvent($request);
    }

    public function joinEvent($eventId)
    {
        return EventService::joinEvent(auth()->id(), $eventId);
    }


    public function removeUser($eventId, $userId)
    {
        return EventService::removeUser($eventId, $userId);
    }


    public function deleteEvent($eventId, Request $request)
    {
        return EventService::deleteEvent($eventId, $request->forceDelete);
    }


    public function exitEvent($eventId)
    {
        return EventService::exitEvent($eventId);
    }


    public function toggleFavourite($eventId)
    {
        return EventService::toggleFavourite($eventId);
    }

    public function requestToJoinEvent($eventId)
    {
        return EventService::requestToJoinEvent(auth()->id(), $eventId);
    }

    public function getEventDetails($eventId)
    {
        return EventService::getEventDetails($eventId);
    }

    public function getUsersToInvite($eventId)
    {
        return EventService::getUsersToInvite($eventId);
    }


}
