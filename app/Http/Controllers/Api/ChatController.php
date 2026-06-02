<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
        // BUAT ROOM CHAT
    public function store(Request $request)
    {
        $user1_id = $request->user1_id;
        $user2_id = $request->user2_id;

        // Check if chat room already exists
        $chat = Chat::where(function($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user1_id)->where('user2_id', $user2_id);
        })->orWhere(function($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user2_id)->where('user2_id', $user1_id);
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $user1_id,
                'user2_id' => $user2_id
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $chat
        ]);
    }

    // AMBIL SEMUA CHAT (milik user tertentu)
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        if ($userId) {
            $chats = Chat::where('user1_id', $userId)
                ->orWhere('user2_id', $userId)
                ->with(['user1', 'user2'])
                ->get()
                ->map(function ($chat) use ($userId) {
                    $otherUser = ($chat->user1_id == $userId) ? $chat->user2 : $chat->user1;
                    $lastMessage = Message::where('chat_id', $chat->id)->latest()->first();

                    return [
                        'id' => $chat->id,
                        'user1_id' => $chat->user1_id,
                        'user2_id' => $chat->user2_id,
                        'other_user' => $otherUser,
                        'last_message' => $lastMessage ? $lastMessage->message : null,
                        'last_message_sender_id' => $lastMessage ? $lastMessage->sender_id : null,
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : $chat->updated_at->toIso8601String(),
                    ];
                })
                ->sortByDesc('last_message_time')
                ->values();

            return response()->json($chats);
        }

        return response()->json(Chat::with(['user1', 'user2'])->get());
    }

    // AMBIL SEMUA CHAT (untuk guru/admin - melihat seluruh aktivitas chat)
    public function allChats(Request $request)
    {
        $chats = Chat::with(['user1', 'user2'])
            ->get()
            ->map(function ($chat) {
                $lastMessage = Message::where('chat_id', $chat->id)->latest()->first();
                $messageCount = Message::where('chat_id', $chat->id)->count();

                return [
                    'id' => $chat->id,
                    'user1' => $chat->user1,
                    'user2' => $chat->user2,
                    'user1_id' => $chat->user1_id,
                    'user2_id' => $chat->user2_id,
                    'last_message' => $lastMessage ? $lastMessage->message : null,
                    'last_message_sender_id' => $lastMessage ? $lastMessage->sender_id : null,
                    'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : $chat->updated_at->toIso8601String(),
                    'message_count' => $messageCount,
                ];
            })
            ->sortByDesc('last_message_time')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }
}
