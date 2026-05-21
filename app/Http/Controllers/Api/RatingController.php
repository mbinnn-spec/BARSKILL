<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    // TAMBAH RATING
    public function store(Request $request)
    {
        $rating = Rating::create([
            'skill_id' => $request->skill_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil dikirim',
            'data' => $rating
        ]);
    }

    // LIHAT RATING
    public function index()
    {
        $ratings = Rating::all();

        return response()->json([
            'success' => true,
            'data' => $ratings
        ]);
    }

}
