<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Occupant;
use Illuminate\Http\Request;

class OccupantController extends Controller
{
    // List all occupants
    public function index()
    {
        $occupants = Occupant::with('house')->get(); // include house relation
        return response()->json($occupants);
    }

    // Store new occupant
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|unique:occupants,nama_lengkap',
            'house_id' => 'nullable|exists:houses,id',
            'user_id' => 'required|integer',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_penghuni' => 'required|in:TETAP,KONTRAK',
            'nomor_telepon' => 'required|string|unique:occupants,nomor_telepon',
            'status_menikah' => 'required|in:SUDAH,BELUM',
        ]);

        $occupant = new Occupant();
        $occupant->nama_lengkap = $request->nama_lengkap;
        $occupant->house_id = $request->house_id;
        $occupant->user_id = $request->user_id;
        $occupant->status_penghuni = $request->status_penghuni;
        $occupant->nomor_telepon = $request->nomor_telepon;
        $occupant->status_menikah = $request->status_menikah;

        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/ktp', $filename);
            $occupant->foto_ktp = 'ktp/' . $filename;
        }

        $occupant->save();

        return response()->json($occupant, 201);
    }

    // Show single occupant
    public function show($id)
    {
        $occupant = Occupant::with('house')->findOrFail($id);
        return response()->json($occupant);
    }

    // Update occupant
    public function update(Request $request, $id)
    {
        $occupant = Occupant::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'sometimes|required|string|unique:occupants,nama_lengkap,'.$id,
            'house_id' => 'nullable|exists:houses,id',
            'user_id' => 'sometimes|required|integer',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_penghuni' => 'sometimes|required|in:TETAP,KONTRAK',
            'nomor_telepon' => 'sometimes|required|string|unique:occupants,nomor_telepon,'.$id,
            'status_menikah' => 'sometimes|required|in:SUDAH,BELUM',
        ]);

        $occupant->fill($request->only([
            'nama_lengkap',
            'house_id',
            'user_id',
            'status_penghuni',
            'nomor_telepon',
            'status_menikah',
        ]));

        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/ktp', $filename);
            $occupant->foto_ktp = 'ktp/' . $filename;
        }

        $occupant->save();

        return response()->json($occupant);
    }

    // Delete occupant
    public function destroy($id)
    {
        $occupant = Occupant::findOrFail($id);
        $occupant->delete();
        return response()->json(['message' => 'Occupant deleted successfully']);
    }
}