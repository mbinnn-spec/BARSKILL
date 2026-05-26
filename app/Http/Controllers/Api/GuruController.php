<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = User::where('role', 'guru')->get();

        return response()->json($guru);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $guru = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru'
        ]);

        return response()->json([
            'message' => 'Akun guru berhasil ditambahkan',
            'data' => $guru
        ]);
    }

    public function show(string $id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        return response()->json($guru);
    }

    public function update(Request $request, string $id)
    {
        $guru = User::findOrFail($id);

        $guru->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Data guru berhasil diupdate',
            'data' => $guru
        ]);
    }

    public function destroy(string $id)
    {
        $guru = User::findOrFail($id);

        $guru->delete();

        return response()->json([
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
