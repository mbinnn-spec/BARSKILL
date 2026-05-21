<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    //  GET semua skill
    public function index()
    {
        return response()->json(Skill::all());
    }

    //  POST tambah skill
    public function store(Request $request)
    {
        $skill = Skill::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description
        ]);

    return response()->json([
        'success' => true,
            'message' => 'Skill berhasil ditambahkan',
            'data' => $skill
        ]);
    }

    //  GET detail skill
    public function show($id)
    {
        $skill = Skill::find($id);

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
            'description' => 'nullable|string'
        ]);

        $skill->update($request->all());

        return response()->json([
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