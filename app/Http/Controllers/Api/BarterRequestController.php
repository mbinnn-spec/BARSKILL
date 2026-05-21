<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarterRequest;

class BarterRequestController extends Controller
{
    public function store(Request $request)
    {
        $barter = BarterRequest::create([
            'skill_id' => $request->skill_id,
            'requester_name' => $request->requester_name,
            'session_date' => $request->session_date,
            'duration' => $request->duration,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ajuan barter berhasil',
            'data' => $barter
        ]);
    }
    public function index()
    {
        $barters = BarterRequest::all();

        return response()->json([
            'success' => true,
            'data' => $barters
        ]);
    }
}
