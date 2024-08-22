<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function removeUser(User $user, Event $event)
    {
        return $event->created_by == $user->id;
    }

    public function deleteEvent(User $user, Event $event)
    {
        return $event->created_by == $user->id;
    }

    public function exitEvent(User $user, Event $event)
    {
        return $event->created_by != $user->id;
    }

    public function joinEvent(User $user, Event $event)
    {
        return $event->created_by != $user->id && $event->is_public;
    }

    public function requestToJoinEvent(User $user, Event $event)
    {
        return $event->created_by != $user->id && !$event->is_public;
    }
}
