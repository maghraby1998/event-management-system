<?php


namespace App\Services;
use App\Jobs\UserJoinEventJob;
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

        $alreadyInvitedUserIds = Invitation::where("event_id", $eventId)->whereIn("receiver_id", $userIds)->pluck("receiver_id")->unique()->toArray();

        $userIds = array_filter($userIds, function ($id) use ($alreadyInvitedUserIds) {
            return !in_array($id, $alreadyInvitedUserIds);
        });

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

    public static function acceptInvitation($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ($invitation->status == "pending") {

            $eventId = $invitation->event_id;

            $receiverId = $invitation->receiver_id;

            $invitation->status = "accepted";

            $invitation->save();

            UserJoinEventJob::dispatch($receiverId, $eventId);

            return response()->json([
                "status" => "success",
                "message" => "Joined"
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "Can't accept invitation because it's already $invitation->status"
            ], 400);
        }
    }

    public static function cancelInvitation($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ($invitation->status == "pending") {

            $invitation->status = "canceled";
            $invitation->save();
            return response()->json([
                "status" => "success",
                "message" => "Invitation has been canceled"
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "You can't cancel the invitation because it's already $invitation->status"
            ]);
        }
    }

    public static function rejectInvitation($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        if ($invitation->status == "pending") {

            $invitation->status = "rejected";
            $invitation->save();

            return response()->json([
                "status" => "success",
                "message" => "Invitation has been rejected"
            ]);
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "You can't cancel the invitation because it's already $invitation->status"
            ]);
        }
    }

}