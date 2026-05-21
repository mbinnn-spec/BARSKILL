<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function index($chat_id)
    {
        $messages = Message::where('chat_id', $chat_id)->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }
}