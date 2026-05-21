<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;

class ChatController extends Controller
{
        // BUAT ROOM CHAT
    public function store(Request $request)
    {
        $chat = Chat::create([
            'user1_id' => $request->user1_id,
            'user2_id' => $request->user2_id
        ]);

        return response()->json([
            'success' => true,
            'data' => $chat
        ]);
    }

    // AMBIL SEMUA CHAT
    public function index()
    {
        return response()->json(Chat::all());
    }
}
