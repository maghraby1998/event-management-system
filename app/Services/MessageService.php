<?php

namespace App\Services;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class MessageService
{
    public static function sendMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $senderId = auth()->id();
            $receiverId = $request->receiverId;

            $chat = Chat::where(function ($query) use ($senderId, $receiverId) {
                $query->where("first_user_id", $senderId)->where("second_user_id", $receiverId);
            })->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where("first_user_id", $receiverId)->where("second_user_id", $senderId);
            })->first();

            if (!$chat) {
                Chat::create([
                    "first_user_id" => $senderId,
                    "second_user_id" => $receiverId,
                ]);
            }

            if ($request->message) {

                $message = Message::create([
                    "sender_id" => $senderId,
                    "message" => $request->message
                ]);
            }

            DB::commit();

            return response()->json([
                "status" => "success",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage(),
            ]);
        }
    }

    public static function getChatMessages($chatId)
    {
        $messages = Message::where("chat_id", $chatId)->get();

        return response()->json([
            "status" => "success",
            "messages" => $messages
        ]);
    }
}