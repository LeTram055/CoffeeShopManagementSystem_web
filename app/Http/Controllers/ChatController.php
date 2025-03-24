<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NewMessageEvent;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message', 'Hello from Laravel!');
        event(new NewMessageEvent($message));
        return response()->json(['status' => 'Message sent successfully']);
    }
}