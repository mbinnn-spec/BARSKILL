<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    //  GET semua skill
    public function index(Request $request)
    {
        $query = Skill::query();
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        } elseif (!$request->status) {
            $query->where('status', 'approved');
        }
        
        return response()->json($query->get());
    }

    //  POST tambah skill
    public function store(Request $request)
    {
        $status = ($request->user_role === 'guru') ? 'approved' : 'pending';

        $skill = Skill::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $status
        ]);

        if ($request->user_id) {
            $skill->users()->attach($request->user_id, [
                'rating' => 0,
                'is_active' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $status === 'pending' 
                ? 'Skill berhasil diajukan dan sedang menunggu persetujuan guru' 
                : 'Skill berhasil ditambahkan',
            'data' => $skill
        ]);
    }

    //  GET detail skill
    public function show($id)
    {
        $skill = Skill::with('users')->find($id);

        if (!$skill) {
            return response()->json([
                'success' => false,
                'message' => 'Skill tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $skill
        ]);
    }

    //  PUT update skill
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|in:akademik,non_akademik',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,approved,rejected'
        ]);

        $skill->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Skill berhasil diupdate',
            'data' => $skill
        ]);
    }

    // 🔹 DELETE skill
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'message' => 'Skill berhasil dihapus'
        ]);
    }

}