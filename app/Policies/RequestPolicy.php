<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Request;

class RequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function acceptRequest(User $user, Request $request) {
        return $request->event->created_by == $user->id;
    }

    public function rejectRequest(User $user, Request $request) {
        return $request->event->created_by == $user->id;
    }

    public function cancelRequest(User $user, Request $request) {
        return $request->user_id == $user->id;
    }
}
