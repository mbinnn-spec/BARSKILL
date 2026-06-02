<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarterRequest;

class BarterRequestController extends Controller
{
    public function store(Request $request)
    {
        try {

            $barter = BarterRequest::create([

                'skill_id' => $request->skill_id,

                'requester_name' => $request->requester_name,

                'session_date' => $request->session_date,

                'duration' => $request->duration,

                'notes' => $request->notes,

                'status' => 'menunggu'
            ]);

            return response()->json([

                'success' => true,

                'message' => 'Ajuan barter berhasil',

                'data' => $barter

            ], 201);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function index()
    {
        try {

            $barters = BarterRequest::with(['skill.users', 'requesterUser'])->get();

            return response()->json([

                'success' => true,

                'data' => $barters

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $barter = BarterRequest::find($id);

            if (!$barter) {

                return response()->json([

                    'success' => false,

                    'message' => 'Barter tidak ditemukan'

                ], 404);
            }

            $request->validate([
                'status' => 'sometimes|required|in:menunggu,disetujui,ditolak,berjalan,selesai',
                'is_rated' => 'sometimes|required|boolean'
            ]);

            if ($request->has('status')) {
                $barter->status = $request->status;
            }
            if ($request->has('is_rated')) {
                $barter->is_rated = $request->is_rated;
            }

            $barter->save();

            return response()->json([

                'success' => true,

                'message' => 'Status berhasil diupdate',

                'data' => $barter

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }
}