<?php

namespace App\Services;
use App\Models\Chat;

class ChatService
{
    public static function getMyChats()
    {
        $chats = Chat::where("first_user_id", auth()->id())->orWhere("second_user_id", auth()->id())->get();

        return $chats;
    }
}