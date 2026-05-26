<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        return response()->json(Siswa::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kelas' => 'required',
            'skill' => 'required'
        ]);

        $siswa = Siswa::create($request->all());

        return response()->json([
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $siswa
        ]);
    }

    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        return response()->json($siswa);
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->update($request->all());

        return response()->json([
            'message' => 'Data siswa berhasil diupdate',
            'data' => $siswa
        ]);
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->delete();

        return response()->json([
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}
