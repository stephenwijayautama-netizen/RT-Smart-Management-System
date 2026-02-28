<?php

namespace App\Http\Controllers;

use App\Models\HouseOccupantHistory;
use Illuminate\Http\Request;

class HouseOccupantHistoryController extends Controller
{
    /**
     * Tampilkan semua data history.
     */
    public function index()
    {
        $histories = HouseOccupantHistory::with(['house', 'occupant'])->get();
        return response()->json($histories);
    }

    /**
     * Simpan history baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_id' => 'nullable|exists:houses,id',
            'occupant_id' => 'required|exists:occupants,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status_aktif' => 'boolean',
        ]);

        $history = HouseOccupantHistory::create($validated);

        return response()->json([
            'message' => 'History berhasil ditambahkan',
            'data' => $history->load(['house', 'occupant'])
        ], 201);
    }

    /**
     * Tampilkan detail history tertentu.
     */
    public function show($id)
    {
        $history = HouseOccupantHistory::with(['house', 'occupant'])->find($id);

        if (!$history) {
            return response()->json(['message' => 'History tidak ditemukan'], 404);
        }

        return response()->json($history);
    }

    /**
     * Update history tertentu.
     */
    public function update(Request $request, $id)
    {
        $history = HouseOccupantHistory::find($id);

        if (!$history) {
            return response()->json(['message' => 'History tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'house_id' => 'nullable|exists:houses,id',
            'occupant_id' => 'nullable|exists:occupants,id',
            'tanggal_masuk' => 'nullable|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status_aktif' => 'boolean',
        ]);

        $history->update($validated);

        return response()->json([
            'message' => 'History berhasil diperbarui',
            'data' => $history->load(['house', 'occupant'])
        ]);
    }

    /**
     * Hapus history tertentu.
     */
    public function destroy($id)
    {
        $history = HouseOccupantHistory::find($id);

        if (!$history) {
            return response()->json(['message' => 'History tidak ditemukan'], 404);
        }

        $history->delete();

        return response()->json(['message' => 'History berhasil dihapus']);
    }
}