<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    // 🔹 GET semua skill
    public function index()
    {
        return response()->json(Skill::all());
    }

    // 🔹 POST tambah skill
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:akademik,non_akademik',
            'description' => 'nullable|string'
        ]);

        $skill = Skill::create($request->all());

        return response()->json([
            'message' => 'Skill berhasil ditambahkan',
            'data' => $skill
        ], 201);
    }

    // 🔹 GET detail skill
    public function show($id)
    {
        $skill = Skill::findOrFail($id);

        return response()->json($skill);
    }

    // 🔹 PUT update skill
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