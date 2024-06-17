<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinEventValidator;
use App\Http\Requests\MakeEventValidator;
use App\Services\EventService;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function events() {
        return EventService::getEvents();
    }

    public function makeEvent(MakeEventValidator $request) {
        return EventService::makeEvent(auth()->id(), $request->name, $request->from, $request->to);
    }

    public function joinEvent(JoinEventValidator $request) {
        return EventService::joinEvent(auth()->id(), $request->eventId);
    }
}
