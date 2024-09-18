<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteUsersToEvent;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function getReceivedInvitations()
    {
        return InvitationService::getReceivedInvitations();
    }
    public function getSentInvitations()
    {
        return InvitationService::getSentInvitations();
    }

    public function inviteUsers(InviteUsersToEvent $request)
    {
        return InvitationService::inviteUsers($request);
    }

    public function acceptInvitation($invitationId)
    {
        return InvitationService::acceptInvitation($invitationId);
    }





}
