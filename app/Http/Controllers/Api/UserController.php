<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

    return response()->json([
        'success' => true,
        'data' => $users
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Akun berhasil ditambahkan',
        'data' => $user
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $user = User::find($id);

        if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ]);
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Akun berhasil diupdate',
        'data' => $user
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ]);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'Akun berhasil dihapus'
    ]);
    }
}
