<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $chat = \App\Models\Chat::find($request->chat_id);
        if ($chat) {
            $user1 = \App\Models\User::find($chat->user1_id);
            $user2 = \App\Models\User::find($chat->user2_id);
            
            if ($user1 && $user2) {
                // Check if barter is proposed (in either direction)
                $isBarterProposed = \App\Models\BarterRequest::where(function($q) use ($user1, $chat) {
                    $q->where('requester_name', $user1->name)
                      ->whereIn('skill_id', function($query) use ($chat) {
                          $query->select('skill_id')
                                ->from('user_skills')
                                ->where('user_id', $chat->user2_id);
                      });
                })->orWhere(function($q) use ($user2, $chat) {
                    $q->where('requester_name', $user2->name)
                      ->whereIn('skill_id', function($query) use ($chat) {
                          $query->select('skill_id')
                                ->from('user_skills')
                                ->where('user_id', $chat->user1_id);
                      });
                })->exists();
                
                // If no barter is proposed, and the initiator is trying to send a message
                if (!$isBarterProposed && $request->sender_id == $chat->user1_id) {
                    // Check the latest message in the chat
                    $latestMessage = Message::where('chat_id', $request->chat_id)
                        ->latest()
                        ->first();
                        
                    // Cooldown only applies if the latest message was from user2 (the receiver)
                    if ($latestMessage && $latestMessage->sender_id == $chat->user2_id) {
                        $elapsed = now()->timestamp - $latestMessage->created_at->timestamp;
                        if ($elapsed >= 300) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Waktu chat telah habis. Silakan ajukan barter untuk melanjutkan obrolan.'
                            ], 403);
                        }
                    }
                }
            }
        }

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
        
        $chat = \App\Models\Chat::find($chat_id);
        $isBarterProposed = false;
        $timeLimitExpired = false;
        $remainingSeconds = null;
        $lastReplyTime = null;
        
        if ($chat) {
            $user1 = \App\Models\User::find($chat->user1_id);
            $user2 = \App\Models\User::find($chat->user2_id);
            
            if ($user1 && $user2) {
                // Check if barter is proposed
                $isBarterProposed = \App\Models\BarterRequest::where(function($q) use ($user1, $chat) {
                    $q->where('requester_name', $user1->name)
                      ->whereIn('skill_id', function($query) use ($chat) {
                          $query->select('skill_id')
                                ->from('user_skills')
                                ->where('user_id', $chat->user2_id);
                      });
                })->orWhere(function($q) use ($user2, $chat) {
                    $q->where('requester_name', $user2->name)
                      ->whereIn('skill_id', function($query) use ($chat) {
                          $query->select('skill_id')
                                ->from('user_skills')
                                ->where('user_id', $chat->user1_id);
                      });
                })->exists();
                
                if (!$isBarterProposed) {
                    // Check the latest message in the chat
                    $latestMessage = Message::where('chat_id', $chat_id)
                        ->latest()
                        ->first();
                        
                    // Cooldown only applies if the latest message was from user2 (the receiver)
                    if ($latestMessage && $latestMessage->sender_id == $chat->user2_id) {
                        $lastReplyTime = $latestMessage->created_at->toIso8601String();
                        $elapsed = now()->timestamp - $latestMessage->created_at->timestamp;
                        if ($elapsed >= 300) {
                            $timeLimitExpired = true;
                            $remainingSeconds = 0;
                        } else {
                            $timeLimitExpired = false;
                            $remainingSeconds = 300 - $elapsed;
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $messages,
            'user1_id' => $chat ? $chat->user1_id : null,
            'is_barter_proposed' => $isBarterProposed,
            'time_limit_expired' => $timeLimitExpired,
            'remaining_seconds' => $remainingSeconds,
            'last_reply_time' => $lastReplyTime
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required',
            'sender_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->hashName();
            
            // Create chat_images directory if it doesn't exist
            $dirPath = public_path('chat_images');
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            
            $file->move($dirPath, $filename);
            $imagePath = 'chat_images/' . $filename;
            
            // Create the message in database
            $message = Message::create([
                'chat_id' => $request->chat_id,
                'sender_id' => $request->sender_id,
                'message' => '__IMAGE__:' . $imagePath
            ]);

            return response()->json([
                'success' => true,
                'data' => $message
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan'
        ], 400);
    }
}