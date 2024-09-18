<?php


namespace App\Services;
use App\Models\Invitation;

class InvitationService
{

    public static function getReceivedInvitations()
    {
        return Invitation::where("receiver_id", auth()->id())->with(["sender:id,name", "receiver:id,name", "event:id,name"])->get()->map(function ($invitation) {
            $invitation->canAccept = $invitation->canAccept();
            $invitation->canReject = $invitation->canReject();
            $invitation->canCancel = $invitation->canCancel();
            return $invitation;
        });
    }

    public static function getSentInvitations()
    {
        return Invitation::where("sender_id", auth()->id())->with(["sender:id,name", "receiver:id,name", "event:id,name"])->get()->map(function ($invitation) {
            $invitation->canAccept = $invitation->canAccept();
            $invitation->canReject = $invitation->canReject();
            $invitation->canCancel = $invitation->canCancel();
            return $invitation;
        });
    }

    public static function inviteUsers($request)
    {
        $eventId = $request->eventId;
        $userIds = $request->userIds;

        $invitations = [];

        foreach ($userIds as $userId) {
            $invitations[] = [
                "event_id" => $eventId,
                "sender_id" => auth()->id(),
                "receiver_id" => $userId,
                "status" => "pending"
            ];
        }

        Invitation::insert($invitations);

        return response()->json([
            "status" => "success",
            "message" => "Invitations has been sent"
        ]);

    }

}