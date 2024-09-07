<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use Illuminate\Http\Request;



class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        return MessageService::sendMessage($request);
    }

    public function getChatMessages($chatId)
    {
        return MessageService::getChatMessages($chatId);
    }
}
