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
        // Cek apakah user sudah pernah memberikan rating untuk skill ini
        $existingRating = Rating::where('skill_id', $request->skill_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan rating untuk skill ini sebelumnya.'
            ], 409);
        }

        // Cek apakah barter_request sudah di-rated (jika barter_request_id diberikan)
        if ($request->has('barter_request_id') && $request->barter_request_id) {
            $barter = \App\Models\BarterRequest::find($request->barter_request_id);
            if ($barter && $barter->is_rated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi barter ini sudah pernah dinilai.'
                ], 409);
            }
        }

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
    public function index(Request $request)
    {
        $query = Rating::with(['user', 'skill']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $ratings = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $ratings
        ]);
    }

}
