<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeEventValidator;
use App\Services\EventService;
use Illuminate\Http\Request;


class EventController extends Controller
{
    /**
     * register.
     *
     * @OA\Get(
     *      path="/api/events",
     *      operationId="events",
     *      tags={"Events"},
     *      summary="get all events",
     *      description="get all the created events",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
    public function events(Request $request)
    {
        return EventService::getEvents($request->search);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/events",
     *      operationId="make event",
     *      tags={"Events"},
     *      summary="make an event",
     *      description="create event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed event"
     *      )
     * )
     */
    public function makeEvent(MakeEventValidator $request)
    {
        return EventService::makeEvent($request);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/events/{eventId}/join",
     *      operationId="join event",
     *      tags={"Events"},
     *      summary="join event",
     *      description="join event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
    public function joinEvent($eventId)
    {
        return EventService::joinEvent(auth()->id(), $eventId);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/events/{eventId}/remove-user/{userId}",
     *      operationId="remove user",
     *      tags={"Events"},
     *      summary="remove user from event",
     *      description="remove user from event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
    public function removeUser($eventId, $userId)
    {
        return EventService::removeUser($eventId, $userId);
    }

    /**
     * register.
     *
     * @OA\Delete(
     *      path="/api/events/{eventId}",
     *      operationId="delete event",
     *      tags={"Events"},
     *      summary="delete event",
     *      description="delete event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
    public function deleteEvent($eventId, Request $request)
    {
        return EventService::deleteEvent($eventId, $request->forceDelete);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/events/{eventId}/exit-event",
     *      operationId="exit event",
     *      tags={"Events"},
     *      summary="exit event",
     *      description="exit event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
    public function exitEvent($eventId)
    {
        return EventService::exitEvent($eventId);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/events/{eventId}/exit-event",
     *      operationId="exit event",
     *      tags={"Events"},
     *      summary="exit event",
     *      description="exit event",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="failed events"
     *      )
     * )
     */
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


}
